<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Room;
use App\Models\Booking;

class RoomController extends Controller
{
    /**
     * Tampilkan halaman room (bisa diakses tanpa login)
     */
    public function index(Request $request)
    {
        // Ambil data room dari database
        $rooms = Room::all();

        // Jika ada parameter check_in dan check_out dari form pencarian
        $checkIn = $request->input('check_in');
        $checkOut = $request->input('check_out');

        // Tambahkan informasi ketersediaan untuk setiap room
        if ($checkIn && $checkOut) {
            foreach ($rooms as $room) {
                $room->available_for_dates = $room->getAvailableQuantityForDate($checkIn, $checkOut);
                $room->is_available = $room->available_for_dates > 0;
            }
        } else {
            // Jika tidak ada tanggal, show default availability
            foreach ($rooms as $room) {
                $room->available_for_dates = $room->total_quantity;
                $room->is_available = true;
            }
        }

        return view('room', compact('rooms', 'checkIn', 'checkOut'));
    }

    /**
     * Proses booking room (butuh login)
     */
    public function book(Request $request, $id)
    {
        // Validasi hanya untuk user yang login
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melakukan booking.');
        }

        // Validasi form booking
        $validated = $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1|max:10',
            'extra_bed' => 'sometimes|boolean',
            'total_cost' => 'required|numeric|min:0',
        ]);

        try {
            // Cari room
            $room = Room::findOrFail($id);

            // Cek ketersediaan kamar untuk tanggal yang diminta
            if (!$room->isAvailableForBooking($validated['check_in'], $validated['check_out'])) {
                return back()->with('error', 'Maaf, kamar tidak tersedia untuk tanggal yang dipilih. Silakan pilih tanggal lain.')
                    ->withInput();
            }

            // Log untuk debugging
            Log::info('Creating booking', [
                'user_id' => Auth::guard('web')->id(),
                'room_id' => $id,
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'guests' => $validated['guests'],
                'total_cost' => $validated['total_cost']
            ]);

            // Buat booking baru
            $booking = Booking::create([
                'user_id' => Auth::guard('web')->id(),
                'room_id' => $id,
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'guests' => $validated['guests'],
                'extra_bed' => $request->has('extra_bed') ? true : false,
                'total_cost' => $validated['total_cost'],
                'status' => 'pending',
                'booking_code' => 'BK' . strtoupper(uniqid()),
            ]);

            Log::info('Booking created successfully', [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code
            ]);

            // Redirect ke payment page dengan booking ID
            return redirect()->route('payment', ['booking' => $booking->id])
                ->with('success', 'Booking berhasil dibuat. Silakan lanjutkan pembayaran.');
        } catch (\Exception $e) {
            Log::error('Booking creation failed: ' . $e->getMessage(), [
                'user_id' => Auth::guard('web')->id(),
                'room_id' => $id,
                'validated_data' => $validated,
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat membuat booking: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Tampilkan history booking user
     */
    public function bookingHistory()
    {
        $user = Auth::user();

        // Ambil booking history dari database
        $bookings = Booking::where('user_id', $user->id)->with('room')->orderBy('created_at', 'desc')->get();
        
        return view('booking.history', compact('bookings'));
    }

    /**
     * Cancel booking (hanya bisa untuk status pending atau awaiting_payment)
     */
    public function cancelBooking(Request $request, $id)
    {
        // Validasi user login
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        try {
            // Ambil booking dan pastikan milik user yang login
            $booking = Booking::where('id', $id)
                ->where('user_id', Auth::guard('web')->id())
                ->first();

            if (!$booking) {
                return redirect()->route('booking.history')->with('error', 'Booking tidak ditemukan.');
            }

            // Hanya bisa cancel booking dengan status pending atau awaiting_payment
            if (!in_array($booking->status, ['pending', 'awaiting_payment'])) {
                return redirect()->route('booking.history')->with('error', 'Booking tidak dapat dibatalkan. Status: ' . $booking->status);
            }

            // Update status booking menjadi cancelled
            $booking->update(['status' => 'cancelled']);

            // Log cancellation
            Log::info('Booking cancelled by user', [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'user_id' => Auth::guard('web')->id(),
                'cancelled_at' => now()
            ]);

            return redirect()->route('booking.history')->with('success', 'Booking berhasil dibatalkan.');
        } catch (\Exception $e) {
            Log::error('Error cancelling booking', [
                'booking_id' => $id,
                'user_id' => Auth::guard('web')->id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->route('booking.history')->with('error', 'Terjadi kesalahan saat membatalkan booking.');
        }
    }

    /**
     * API untuk cek ketersediaan real-time via AJAX
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        $room = Room::findOrFail($request->room_id);
        $availableQuantity = $room->getAvailableQuantityForDate($request->check_in, $request->check_out);

        return response()->json([
            'available' => $availableQuantity > 0,
            'available_quantity' => $availableQuantity,
            'total_quantity' => $room->total_quantity,
            'message' => $availableQuantity > 0
                ? "Tersedia {$availableQuantity} dari {$room->total_quantity} kamar"
                : "Tidak ada kamar tersedia untuk tanggal tersebut"
        ]);
    }
}
