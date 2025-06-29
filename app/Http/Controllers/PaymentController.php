<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set your Merchant Server Key
        Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = config('midtrans.is_production');
        // Set sanitization on (default)
        Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        Config::$is3ds = true;
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
            return redirect()->route('room')->with('error', 'Booking tidak ditemukan.');
        }

        // Pastikan booking masih pending
        if ($booking->status !== 'pending') {
            return redirect()->route('profile')->with('error', 'Booking ini sudah diproses.');
        }

        // Buat transaction details untuk Midtrans
        $transactionDetails = [
            'order_id' => $booking->booking_code,
            'gross_amount' => (int) $booking->total_cost,
        ];

        // Item details
        $itemDetails = [
            [
                'id' => $booking->room->id,
                'price' => (int) $booking->total_cost,
                'quantity' => 1,
                'name' => $booking->room->name . ' - Booking',
                'brand' => 'Bayang Brothers Hotel',
                'category' => 'Hotel Room',
            ]
        ];

        // Customer details
        $customerDetails = [
            'first_name' => $booking->user->name,
            'email' => $booking->user->email,
            'phone' => $booking->user->phone ?? '',
        ];

        // Transaction data
        $transactionData = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
        ];

        try {
            $snapToken = Snap::getSnapToken($transactionData);
            return view('payment', compact('booking', 'snapToken'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Handle notification dari Midtrans
     */
    public function notification(Request $request)
    {
        try {
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id;
            $fraudStatus = $notification->fraud_status;

            // Cari booking berdasarkan order_id (booking_code)
            $booking = Booking::where('booking_code', $orderId)->first();

            if (!$booking) {
                return response()->json(['status' => 'error', 'message' => 'Booking not found'], 404);
            }

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $booking->update(['status' => 'pending']);
                } else if ($fraudStatus == 'accept') {
                    $booking->update(['status' => 'confirmed']);
                }
            } else if ($transactionStatus == 'settlement') {
                $booking->update(['status' => 'confirmed']);
            } else if ($transactionStatus == 'pending') {
                $booking->update(['status' => 'pending']);
            } else if ($transactionStatus == 'deny') {
                $booking->update(['status' => 'cancelled']);
            } else if ($transactionStatus == 'expire') {
                $booking->update(['status' => 'cancelled']);
            } else if ($transactionStatus == 'cancel') {
                $booking->update(['status' => 'cancelled']);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle finish payment dari Midtrans
     */
    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $transactionStatus = $request->transaction_status;

        // Cari booking berdasarkan order_id
        $booking = Booking::where('booking_code', $orderId)->first();

        if (!$booking) {
            return redirect()->route('room')->with('error', 'Booking tidak ditemukan.');
        }

        if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
            return redirect()->route('profile')->with('success', 'Pembayaran berhasil! Booking Anda telah dikonfirmasi.');
        } else if ($transactionStatus === 'pending') {
            return redirect()->route('profile')->with('info', 'Pembayaran sedang diproses. Kami akan menghubungi Anda jika ada update.');
        } else {
            return redirect()->route('profile')->with('error', 'Pembayaran gagal atau dibatalkan.');
        }
    }

    /**
     * Handle unfinish payment dari Midtrans
     */
    public function unfinish(Request $request)
    {
        return redirect()->route('profile')->with('warning', 'Pembayaran belum selesai. Anda dapat melanjutkan pembayaran kapan saja.');
    }

    /**
     * Handle error payment dari Midtrans
     */
    public function error(Request $request)
    {
        return redirect()->route('profile')->with('error', 'Terjadi kesalahan saat melakukan pembayaran. Silakan coba lagi.');
    }
}
