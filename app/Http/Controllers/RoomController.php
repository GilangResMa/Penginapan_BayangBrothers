<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use App\Models\Booking;

class RoomController extends Controller
{
    /**
     * Tampilkan halaman room (bisa diakses tanpa login)
     */
    public function index()
    {
        // Ambil data room dari database
        $rooms = Room::all();

        return view('room', compact('rooms'));
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

            // Redirect ke payment page dengan booking ID
            return redirect()->route('payment', ['booking' => $booking->id])->with('success', 'Booking berhasil dibuat. Silakan lanjutkan pembayaran.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat membuat booking. Silakan coba lagi.')->withInput();
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
}
