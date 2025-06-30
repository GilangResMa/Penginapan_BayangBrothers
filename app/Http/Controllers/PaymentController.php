<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;

class PaymentController extends Controller
{
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
     * Proses konfirmasi pembayaran
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
}