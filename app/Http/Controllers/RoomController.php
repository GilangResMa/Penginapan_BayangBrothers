<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    /**
     * Tampilkan halaman room (bisa diakses tanpa login)
     */
    public function index()
    {
        // Data room (bisa dari database atau hardcoded)
        $rooms = [
            [
                'id' => 1,
                'name' => 'Bayang Brothers',
                'price' => 150000,
                'description' => 'Kamar mewah dengan fasilitas lengkap',
                'image' => 'room1.jpg'
            ],
        ];

        return view('room', compact('rooms'));
    }

    /**
     * Proses booking room (butuh login)
     */
    public function book(Request $request, $id)
    {
        // Validasi input booking
        $request->validate([
            'check_in' => 'required|date|after:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1|max:4'
        ]);

        // Logic untuk save booking ke database
        $user = Auth::user();
        
        // Simpan booking (sesuaikan dengan struktur database Anda)
        // Booking::create([
        //     'user_id' => $user->id,
        //     'room_id' => $id,
        //     'check_in' => $request->check_in,
        //     'check_out' => $request->check_out,
        //     'guests' => $request->guests,
        //     'status' => 'pending'
        // ]);

        return redirect()->back()->with('success', 'Booking berhasil! Kami akan menghubungi Anda segera.');
    }

    /**
     * Tampilkan history booking user
     */
    public function bookingHistory()
    {
        $user = Auth::user();
        
        // Ambil booking history dari database
        // $bookings = Booking::where('user_id', $user->id)->with('room')->get();
        
        $bookings = []; // Temporary data kosong
        
        return view('booking.history', compact('bookings'));
    }
}
