<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Pastikan package Midtrans tersedia
        if (!class_exists('\Midtrans\Config')) {
            Log::error('Midtrans package not found. Please install: composer require midtrans/midtrans-php');
            throw new \Exception('Midtrans package not installed');
        }

        // Set your Merchant Server Key
        Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = config('midtrans.is_production', false);
        // Set sanitization on (default)
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        // Set 3DS transaction for credit card to true
        Config::$is3ds = config('midtrans.is_3ds', true);

        // Tambahkan logging untuk debugging
        Log::info('Midtrans Config initialized:', [
            'server_key_exists' => !empty(Config::$serverKey),
            'server_key_preview' => Config::$serverKey ? substr(Config::$serverKey, 0, 10) . '...' : 'NOT SET',
            'is_production' => Config::$isProduction,
            'is_sanitized' => Config::$isSanitized,
            'is_3ds' => Config::$is3ds
        ]);

        // Validasi konfigurasi penting
        if (empty(Config::$serverKey)) {
            Log::error('Midtrans server key is empty! Check your .env file.');
        }
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
            // Pastikan package Midtrans tersedia
            if (!class_exists('\Midtrans\Snap')) {
                throw new \Exception('Midtrans Snap class not found. Please install midtrans/midtrans-php package.');
            }

            // Validasi konfigurasi Midtrans
            if (empty(Config::$serverKey) || Config::$serverKey === 'your-server-key') {
                throw new \Exception('Midtrans server key belum dikonfigurasi. Silakan periksa file .env');
            }

            // Validasi client key juga
            $clientKey = config('midtrans.client_key');
            if (empty($clientKey)) {
                throw new \Exception('Midtrans client key belum dikonfigurasi. Silakan periksa file .env');
            }

            // Tambahkan logging untuk debugging
            Log::info('Creating Midtrans transaction', [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'amount' => $booking->total_cost,
                'user_id' => $booking->user_id,
                'transaction_data' => $transactionData
            ]);

            $snapToken = Snap::getSnapToken($transactionData);

            Log::info('Midtrans snap token generated successfully', [
                'booking_code' => $booking->booking_code,
                'token_length' => strlen($snapToken)
            ]);

            return view('payment', compact('booking', 'snapToken'));
        } catch (\Exception $e) {
            Log::error('Midtrans error: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
                'transaction_data' => $transactionData,
                'config_check' => [
                    'server_key_set' => !empty(Config::$serverKey),
                    'client_key_set' => !empty(config('midtrans.client_key')),
                    'is_production' => Config::$isProduction
                ],
                'trace' => $e->getTraceAsString()
            ]);

            // Pesan error yang lebih informatif
            $errorMessage = 'Terjadi kesalahan saat memproses pembayaran: ';
            if (strpos($e->getMessage(), 'server_key') !== false) {
                $errorMessage .= 'Konfigurasi Midtrans belum lengkap.';
            } elseif (strpos($e->getMessage(), 'curl') !== false) {
                $errorMessage .= 'Koneksi ke server pembayaran gagal. Periksa koneksi internet.';
            } else {
                $errorMessage .= $e->getMessage();
            }

            return back()->with('error', $errorMessage);
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
