<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Str;

// Midtrans Integration
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Remove Midtrans configuration as we're using manual payment
    }

    /**
     * Tampilkan halaman payment
     */
    public function show($bookingId)
    {
        // Validasi user login
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data booking
        $booking = Booking::with('room', 'user')->findOrFail($bookingId);

        // Pastikan booking milik user yang login
        if ($booking->user_id !== Auth::guard('web')->id()) {
            return redirect()->route('room.index')->with('error', 'Booking tidak ditemukan.');
        }

        // Pastikan booking masih pending
        if ($booking->status !== 'pending') {
            return redirect()->route('profile')->with('error', 'Booking ini sudah diproses.');
        }

        return view('payment', compact('booking'));
    }

    /**
     * Create Midtrans Snap Token for payment
     */
    public function createSnapToken(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'booking_id' => 'required|exists:bookings,id',
                'payment_method' => 'required|in:bank_transfer,credit_card,digital_wallet',
                'total_amount' => 'required|numeric|min:1',
                'customer_details' => 'required|array',
                'customer_details.first_name' => 'required|string|max:255',
                'customer_details.email' => 'required|email|max:255',
                'customer_details.phone' => 'required|string|max:20',
                'bank' => 'nullable|string',
                'wallet' => 'nullable|string',
            ]);

            // Get booking data
            $booking = Booking::with('room', 'user')->findOrFail($validated['booking_id']);

            // Ensure booking belongs to authenticated user
            if ($booking->user_id !== Auth::guard('web')->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to booking'
                ], 403);
            }

            // Generate unique order ID
            $orderId = 'BB-' . date('YmdHis') . '-' . $booking->id;

            // Prepare transaction details
            $transactionDetails = [
                'order_id' => $orderId,
                'gross_amount' => (int) $validated['total_amount'],
            ];

            // Customer details
            $customerDetails = [
                'first_name' => $validated['customer_details']['first_name'],
                'email' => $validated['customer_details']['email'],
                'phone' => $validated['customer_details']['phone'],
            ];

            // Calculate item details
            $nights = \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out));
            $roomPrice = $booking->room->price * $nights;
            $extraBedPrice = $booking->extra_bed ? 50000 : 0;
            $taxAmount = ($roomPrice + $extraBedPrice) * 0.1;

            // Item details
            $itemDetails = [
                [
                    'id' => 'room-' . $booking->room->id,
                    'price' => (int) $booking->room->price,
                    'quantity' => $nights,
                    'name' => $booking->room->name . ' (' . $nights . ' nights)',
                ]
            ];

            if ($extraBedPrice > 0) {
                $itemDetails[] = [
                    'id' => 'extra-bed',
                    'price' => (int) $extraBedPrice,
                    'quantity' => 1,
                    'name' => 'Extra Bed',
                ];
            }

            if ($taxAmount > 0) {
                $itemDetails[] = [
                    'id' => 'tax-service',
                    'price' => (int) $taxAmount,
                    'quantity' => 1,
                    'name' => 'Tax & Service (10%)',
                ];
            }

            // Prepare payment parameters
            $params = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
                'callbacks' => [
                    'finish' => url('/payment/finish/' . $booking->id),
                    'unfinish' => url('/payment/unfinish/' . $booking->id),
                    'error' => url('/payment/error/' . $booking->id),
                ]
            ];

            // Configure enabled payment methods based on selection
            $enabledPayments = [];

            switch ($validated['payment_method']) {
                case 'bank_transfer':
                    $enabledPayments = ['bank_transfer'];
                    if (!empty($validated['bank'])) {
                        $params['bank_transfer'] = [
                            'bank' => $validated['bank']
                        ];
                    }
                    break;

                case 'credit_card':
                    $enabledPayments = ['credit_card'];
                    break;

                case 'digital_wallet':
                    if (!empty($validated['wallet'])) {
                        switch ($validated['wallet']) {
                            case 'gopay':
                                $enabledPayments = ['gopay'];
                                break;
                            case 'shopeepay':
                                $enabledPayments = ['shopeepay'];
                                break;
                            case 'dana':
                            case 'ovo':
                                $enabledPayments = ['other_qris'];
                                break;
                            default:
                                $enabledPayments = ['gopay', 'shopeepay'];
                                break;
                        }
                    } else {
                        $enabledPayments = ['gopay', 'shopeepay'];
                    }
                    break;
            }

            $params['enabled_payments'] = $enabledPayments;

            // Get Snap Token
            $snapToken = Snap::getSnapToken($params);

            // Update booking with payment details
            $booking->update([
                'payment_method' => $validated['payment_method'],
                'snap_token' => $snapToken,
                'order_id' => $orderId,
                'status' => 'awaiting_payment'
            ]);

            Log::info('Snap token created successfully', [
                'booking_id' => $booking->id,
                'order_id' => $orderId,
                'payment_method' => $validated['payment_method']
            ]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $orderId,
                'message' => 'Payment token created successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create snap token: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle Midtrans notification callback
     */
    public function handleNotification(Request $request)
    {
        try {
            $notif = new \Midtrans\Notification();

            $transactionStatus = $notif->transaction_status;
            $orderId = $notif->order_id;
            $fraudStatus = $notif->fraud_status ?? 'accept';

            Log::info('Midtrans notification received', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus
            ]);

            // Find booking by order_id
            $booking = Booking::where('order_id', $orderId)->first();

            if (!$booking) {
                Log::error('Booking not found for order_id: ' . $orderId);
                return response()->json(['status' => 'error', 'message' => 'Booking not found'], 404);
            }

            // Handle different transaction statuses
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $booking->update(['status' => 'challenge']);
                } else if ($fraudStatus == 'accept') {
                    $booking->update(['status' => 'confirmed']);
                }
            } else if ($transactionStatus == 'settlement') {
                $booking->update(['status' => 'confirmed']);
            } else if ($transactionStatus == 'pending') {
                $booking->update(['status' => 'awaiting_payment']);
            } else if ($transactionStatus == 'deny') {
                $booking->update(['status' => 'failed']);
            } else if ($transactionStatus == 'expire') {
                $booking->update(['status' => 'expired']);
            } else if ($transactionStatus == 'cancel') {
                $booking->update(['status' => 'cancelled']);
            }

            Log::info('Booking status updated', [
                'booking_id' => $booking->id,
                'order_id' => $orderId,
                'new_status' => $booking->status
            ]);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Error handling Midtrans notification: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Proses konfirmasi pembayaran (Legacy method for non-Midtrans)
     */
    public function process(Request $request, $bookingId)
    {
        // Validasi user login
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Validasi input
        $validated = $request->validate([
            'payment_method' => 'required|in:bank_transfer,cash,digital_wallet',
            'payment_note' => 'nullable|string|max:500',
        ]);

        // Ambil data booking
        $booking = Booking::with('room', 'user')->findOrFail($bookingId);

        // Pastikan booking milik user yang login
        if ($booking->user_id !== Auth::guard('web')->id()) {
            return redirect()->route('room.index')->with('error', 'Booking tidak ditemukan.');
        }

        // Pastikan booking masih pending
        if ($booking->status !== 'pending') {
            return redirect()->route('profile')->with('error', 'Booking ini sudah diproses.');
        }

        try {
            // Update booking dengan metode pembayaran
            $booking->update([
                'payment_method' => $validated['payment_method'],
                'payment_note' => $validated['payment_note'],
                'status' => 'awaiting_payment' // Status baru untuk menunggu konfirmasi admin
            ]);

            Log::info('Payment method selected for booking', [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'payment_method' => $validated['payment_method'],
                'user_id' => Auth::guard('web')->id()
            ]);

            return redirect()->route('payment.success', ['booking' => $booking->id])
                ->with('success', 'Metode pembayaran berhasil dipilih. Silakan lakukan pembayaran sesuai instruksi.');
        } catch (\Exception $e) {
            Log::error('Payment processing failed: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
                'user_id' => Auth::guard('web')->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Handle payment finish callback
     */
    public function paymentFinish($bookingId)
    {
        $booking = Booking::with('room', 'user')->findOrFail($bookingId);

        return redirect()->route('payment.success', $booking->id)
            ->with('success', 'Payment completed successfully!');
    }

    /**
     * Handle payment unfinish callback  
     */
    public function paymentUnfinish($bookingId)
    {
        $booking = Booking::with('room', 'user')->findOrFail($bookingId);

        return redirect()->route('payment', $booking->id)
            ->with('info', 'Payment not completed. Please complete your payment.');
    }

    /**
     * Handle payment error callback
     */
    public function paymentError($bookingId)
    {
        $booking = Booking::with('room', 'user')->findOrFail($bookingId);

        return redirect()->route('payment', $booking->id)
            ->with('error', 'Payment failed. Please try again.');
    }

    /**
     * Show payment pending page
     */
    public function paymentPending($bookingId)
    {
        // Validasi user login
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data booking dengan payment
        $booking = Booking::with(['room', 'user', 'payment'])->findOrFail($bookingId);

        // Pastikan booking milik user yang login
        if ($booking->user_id !== Auth::guard('web')->id()) {
            return redirect()->route('room.index')->with('error', 'Booking tidak ditemukan.');
        }

        // Pastikan booking sudah ada payment
        if (!$booking->payment || $booking->status !== 'awaiting_payment') {
            return redirect()->route('profile')->with('error', 'Status pembayaran tidak valid.');
        }

        return view('payment-pending', compact('booking'));
    }

    /**
     * Halaman sukses setelah memilih metode pembayaran
     */
    public function success($bookingId)
    {
        // Validasi user login
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data booking
        $booking = Booking::with('room', 'user')->findOrFail($bookingId);

        // Pastikan booking milik user yang login
        if ($booking->user_id !== Auth::guard('web')->id()) {
            return redirect()->route('room.index')->with('error', 'Booking tidak ditemukan.');
        }

        return view('payment-success', compact('booking'));
    }

    /**
     * Handle pembatalan booking
     */
    public function cancel($bookingId = null)
    {
        // Jika tidak ada booking ID, tampilkan halaman cancel umum
        if (!$bookingId) {
            return view('payment-cancel');
        }

        // Validasi user login
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data booking
        $booking = Booking::findOrFail($bookingId);

        // Pastikan booking milik user yang login
        if ($booking->user_id !== Auth::guard('web')->id()) {
            return redirect()->route('room.index')->with('error', 'Booking tidak ditemukan.');
        }

        // Hanya bisa dibatalkan jika masih pending atau awaiting_payment
        if (!in_array($booking->status, ['pending', 'awaiting_payment'])) {
            return redirect()->route('profile')->with('error', 'Booking ini tidak dapat dibatalkan.');
        }

        try {
            $booking->update(['status' => 'cancelled']);

            Log::info('Booking cancelled by user', [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'user_id' => Auth::guard('web')->id()
            ]);

            return view('payment-cancel')->with('success', 'Booking berhasil dibatalkan.');
        } catch (\Exception $e) {
            Log::error('Booking cancellation failed: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
                'user_id' => Auth::guard('web')->id()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat membatalkan booking.');
        }
    }

    /**
     * Upload bukti pembayaran
     */
    public function uploadPayment(Request $request, $bookingId)
    {
        // Validasi user login
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Validasi input
        $validated = $request->validate([
            'payment_method' => 'required|in:qris,bank_transfer',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ]);

        // Ambil data booking
        $booking = Booking::with('room', 'user')->findOrFail($bookingId);

        // Pastikan booking milik user yang login
        if ($booking->user_id !== Auth::guard('web')->id()) {
            return redirect()->route('room.index')->with('error', 'Booking tidak ditemukan.');
        }

        // Pastikan booking masih pending
        if ($booking->status !== 'pending') {
            return redirect()->route('profile')->with('error', 'Booking ini sudah diproses.');
        }

        try {
            // Upload file bukti pembayaran
            $file = $request->file('payment_proof');
            $filename = 'payment_proof_' . $booking->booking_code . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('payment_proofs', $filename, 'public');

            // Simpan data payment
            Payment::create([
                'booking_id' => $booking->id,
                'payment_method' => $validated['payment_method'],
                'amount' => $booking->total_cost,
                'payment_proof' => $filePath,
                'status' => 'pending'
            ]);

            // Update status booking menjadi awaiting_payment
            $booking->update(['status' => 'awaiting_payment']);

            return redirect()->route('payment.pending', $booking->id)
                ->with('success', 'Bukti pembayaran berhasil diupload. Admin akan memverifikasi pembayaran Anda dalam 2x24 jam.');
        } catch (\Exception $e) {
            Log::error('Error uploading payment proof', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat mengupload bukti pembayaran. Silakan coba lagi.');
        }
    }
}