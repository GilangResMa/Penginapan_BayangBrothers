<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Owner;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use App\Models\Payment;
use Carbon\Carbon;

class OwnerController extends Controller
{
    // Note: Middleware sudah diterapkan di routes, tidak perlu di constructor
    
    /**
     * Owner Dashboard
     */
    public function dashboard()
    {
        $owner = Auth::guard('owner')->user();

        // Get owner's rooms first - if none exist, assign all rooms to this owner temporarily
        $ownerRooms = Room::where('owner_id', $owner->id)->pluck('id');

        // If owner has no rooms, assign all existing rooms to this owner
        if ($ownerRooms->isEmpty()) {
            Room::whereNull('owner_id')->orWhere('owner_id', 0)->update(['owner_id' => $owner->id]);
            $ownerRooms = Room::where('owner_id', $owner->id)->pluck('id');
        }

        // Statistics based on owner's rooms
        $totalBookings = Booking::whereIn('room_id', $ownerRooms)->count();

        // Total revenue from verified payments
        $totalRevenue = Payment::whereHas('booking', function ($q) use ($ownerRooms) {
            $q->whereIn('room_id', $ownerRooms);
        })->where('status', 'verified')->sum('amount');

        // If no payments exist, calculate from bookings for fallback
        if ($totalRevenue == 0) {
            $totalRevenue = Booking::whereIn('room_id', $ownerRooms)
                ->whereIn('status', ['confirmed', 'completed'])
                ->sum('total_cost');
        }

        // Monthly revenue for current month from verified payments
        $monthlyRevenue = Payment::whereHas('booking', function ($q) use ($ownerRooms) {
            $q->whereIn('room_id', $ownerRooms);
        })->where('status', 'verified')
            ->whereMonth('verified_at', now()->month)
            ->whereYear('verified_at', now()->year)
            ->sum('amount');

        // Fallback to booking total_cost if no payments
        if ($monthlyRevenue == 0) {
            $monthlyRevenue = Booking::whereIn('room_id', $ownerRooms)
                ->whereIn('status', ['confirmed', 'completed'])
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_cost');
        }
        
        $totalAdmins = Admin::where('created_by', $owner->id)->count();
        $totalRooms = $ownerRooms->count();

        // Payment statistics
        $pendingPayments = Payment::whereHas('booking', function ($q) use ($ownerRooms) {
            $q->whereIn('room_id', $ownerRooms);
        })->where('status', 'pending')->count();

        $verifiedPayments = Payment::whereHas('booking', function ($q) use ($ownerRooms) {
            $q->whereIn('room_id', $ownerRooms);
        })->where('status', 'verified')->count();

        // Recent bookings
        $recentBookings = Booking::whereIn('room_id', $ownerRooms)
            ->with(['room', 'user', 'payment'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Monthly revenue chart data for the last 12 months
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);

            // Try payments first
            $revenue = Payment::whereHas('booking', function ($q) use ($ownerRooms) {
                $q->whereIn('room_id', $ownerRooms);
            })->where('status', 'verified')
                ->whereYear('verified_at', $date->year)
                ->whereMonth('verified_at', $date->month)
                ->sum('amount');

            // Fallback to bookings if no payments
            if ($revenue == 0) {
                $revenue = Booking::whereIn('room_id', $ownerRooms)
                    ->whereIn('status', ['confirmed', 'completed'])
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('total_cost');
            }
            
            $monthlyData[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue
            ];
        }

        // Booking status statistics
        $bookingStats = [
            'total' => $totalBookings,
            'confirmed' => Booking::whereIn('room_id', $ownerRooms)->where('status', 'confirmed')->count(),
            'pending' => Booking::whereIn('room_id', $ownerRooms)->whereIn('status', ['pending', 'awaiting_payment'])->count(),
            'cancelled' => Booking::whereIn('room_id', $ownerRooms)->where('status', 'cancelled')->count(),
            'completed' => Booking::whereIn('room_id', $ownerRooms)->where('status', 'completed')->count(),
        ];
        
        $bookingStats['success_rate'] = $bookingStats['total'] > 0
            ? round((($bookingStats['confirmed'] + $bookingStats['completed']) / $bookingStats['total']) * 100, 2)
            : 0;

        return view('owner.dashboard', compact(
            'owner',
            'totalBookings',
            'totalRevenue',
            'monthlyRevenue',
            'totalAdmins',
            'totalRooms',
            'pendingPayments',
            'verifiedPayments',
            'recentBookings',
            'monthlyData',
            'bookingStats',
            'ownerRooms'
        ));
    }

    /**
     * Show all bookings
     */
    public function bookings(Request $request)
    {
        $owner = Auth::guard('owner')->user();
        $ownerRooms = Room::where('owner_id', $owner->id)->pluck('id');

        // If owner has no rooms, assign all existing rooms to this owner
        if ($ownerRooms->isEmpty()) {
            Room::whereNull('owner_id')->orWhere('owner_id', 0)->update(['owner_id' => $owner->id]);
            $ownerRooms = Room::where('owner_id', $owner->id)->pluck('id');
        }

        $query = Booking::whereIn('room_id', $ownerRooms)->with(['room', 'user', 'payment']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('check_in', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('check_out', '<=', $request->date_to);
        }

        // Search by booking code or user name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('owner.bookings', compact('bookings'));
    }

    /**
     * Show booking details
     */
    public function showBooking($id)
    {
        $owner = Auth::guard('owner')->user();
        $ownerRooms = Room::where('owner_id', $owner->id)->pluck('id');

        // If owner has no rooms, assign all existing rooms to this owner
        if ($ownerRooms->isEmpty()) {
            Room::whereNull('owner_id')->orWhere('owner_id', 0)->update(['owner_id' => $owner->id]);
            $ownerRooms = Room::where('owner_id', $owner->id)->pluck('id');
        }

        $booking = Booking::whereIn('room_id', $ownerRooms)
            ->with(['room', 'user', 'payment', 'payment.verifiedBy'])
                         ->findOrFail($id);

        return view('owner.booking-detail', compact('booking'));
    }

    /**
     * Revenue analytics
     */
    public function revenue(Request $request)
    {
        $owner = Auth::guard('owner')->user();
        $ownerRooms = Room::where('owner_id', $owner->id)->pluck('id');

        // If owner has no rooms, assign all existing rooms to this owner
        if ($ownerRooms->isEmpty()) {
            Room::whereNull('owner_id')->orWhere('owner_id', 0)->update(['owner_id' => $owner->id]);
            $ownerRooms = Room::where('owner_id', $owner->id)->pluck('id');
        }

        // Default to current year
        $year = $request->get('year', date('Y'));
        $month = $request->get('month');

        // Monthly revenue for the selected year
        $monthlyRevenue = [];
        for ($i = 1; $i <= 12; $i++) {
            $revenue = Payment::whereHas('booking', function ($q) use ($ownerRooms) {
                $q->whereIn('room_id', $ownerRooms);
            })->where('status', 'verified')
                ->whereYear('verified_at', $year)
                ->whereMonth('verified_at', $i)
                ->sum('amount');

            // Fallback to bookings if no payments
            if ($revenue == 0) {
                $revenue = Booking::whereIn('room_id', $ownerRooms)
                    ->whereIn('status', ['confirmed', 'completed'])
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $i)
                    ->sum('total_cost');
            }
            
            $monthlyRevenue[] = [
                'month' => Carbon::create($year, $i, 1)->format('M'),
                'revenue' => $revenue
            ];
        }

        // Top performing rooms
        $topRooms = Room::where('owner_id', $owner->id)
            ->select('rooms.*')
            ->selectSub(function ($query) use ($year, $month, $ownerRooms) {
                $query->from('payments')
                    ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                    ->selectRaw('COALESCE(SUM(payments.amount), 0)')
                     ->whereColumn('bookings.room_id', 'rooms.id')
                ->where('payments.status', 'verified');
                
                if ($year) {
                $query->whereYear('payments.verified_at', $year);
                }
                if ($month) {
                $query->whereMonth('payments.verified_at', $month);
                }
            }, 'total_revenue')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get();

        // Year-over-year comparison
        $currentYearRevenue = Payment::whereHas('booking', function ($q) use ($ownerRooms) {
            $q->whereIn('room_id', $ownerRooms);
        })->where('status', 'verified')
            ->whereYear('verified_at', $year)
            ->sum('amount');

        // Fallback to bookings if no payments
        if ($currentYearRevenue == 0) {
            $currentYearRevenue = Booking::whereIn('room_id', $ownerRooms)
                ->whereIn('status', ['confirmed', 'completed'])
                ->whereYear('created_at', $year)
                ->sum('total_cost');
        }

        $previousYearRevenue = Payment::whereHas('booking', function ($q) use ($ownerRooms) {
            $q->whereIn('room_id', $ownerRooms);
        })->where('status', 'verified')
            ->whereYear('verified_at', $year - 1)
            ->sum('amount');

        // Fallback to bookings if no payments
        if ($previousYearRevenue == 0) {
            $previousYearRevenue = Booking::whereIn('room_id', $ownerRooms)
                ->whereIn('status', ['confirmed', 'completed'])
                ->whereYear('created_at', $year - 1)
                ->sum('total_cost');
        }

        $revenueGrowth = $previousYearRevenue > 0 
            ? (($currentYearRevenue - $previousYearRevenue) / $previousYearRevenue) * 100 
            : 0;

        // Available years for filter
        $availableYears = Payment::whereHas('booking', function ($q) use ($ownerRooms) {
            $q->whereIn('room_id', $ownerRooms);
        })->where('status', 'verified')
            ->selectRaw('YEAR(verified_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('owner.revenue', compact(
            'monthlyRevenue',
            'topRooms',
            'currentYearRevenue',
            'previousYearRevenue',
            'revenueGrowth',
            'availableYears',
            'year',
            'month'
        ));
    }

    /**
     * List all admins
     */
    public function admins()
    {
        $owner = Auth::guard('owner')->user();
        $admins = Admin::where('created_by', $owner->id)
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);

        return view('owner.admins', compact('admins'));
    }

    /**
     * Show form to create new admin
     */
    public function createAdmin()
    {
        return view('owner.admin-create');
    }

    /**
     * Store new admin
     */
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'nullable|boolean',
        ]);

        $owner = Auth::guard('owner')->user();

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => $request->has('status') ? true : false,
            'created_by' => $owner->id,
        ]);

        return redirect('/owner/admins')->with('success', 'Admin berhasil ditambahkan!');
    }

    /**
     * Show form to edit admin
     */
    public function editAdmin($id)
    {
        $owner = Auth::guard('owner')->user();
        $admin = Admin::where('created_by', $owner->id)->findOrFail($id);

        return view('owner.admin-edit', compact('admin'));
    }

    /**
     * Update admin
     */
    public function updateAdmin(Request $request, $id)
    {
        $owner = Auth::guard('owner')->user();
        $admin = Admin::where('created_by', $owner->id)->findOrFail($id);

        // Handle activate/deactivate actions
        if ($request->has('action')) {
            if ($request->action === 'activate') {
                $admin->update(['status' => true]);
                return redirect()->route('owner.admins')->with('success', 'Admin berhasil diaktifkan!');
            } elseif ($request->action === 'deactivate') {
                $admin->update(['status' => false]);
                return redirect()->route('owner.admins')->with('success', 'Admin berhasil dinonaktifkan!');
            }
        }

        // Regular update validation for edit form
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'required|boolean',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $admin->update($updateData);

        return redirect()->route('owner.admins')->with('success', 'Admin berhasil diperbarui!');
    }

    /**
     * Delete admin
     */
    public function deleteAdmin($id)
    {
        $owner = Auth::guard('owner')->user();
        $admin = Admin::where('created_by', $owner->id)->findOrFail($id);
        
        $admin->delete();

        return redirect()->route('owner.admins')->with('success', 'Admin berhasil dihapus!');
    }

    /**
     * Export revenue report
     */
    public function exportRevenue(Request $request)
    {
        $owner = Auth::guard('owner')->user();
        $ownerRooms = Room::where('owner_id', $owner->id)->pluck('id');
        
        $year = $request->get('year', date('Y'));
        $month = $request->get('month');

        $query = Payment::whereHas('booking', function ($q) use ($ownerRooms) {
            $q->whereIn('room_id', $ownerRooms);
        })->where('status', 'verified')
            ->with(['booking.room', 'booking.user']);

        if ($year) {
            $query->whereYear('verified_at', $year);
        }

        if ($month) {
            $query->whereMonth('verified_at', $month);
        }

        $payments = $query->orderBy('verified_at', 'desc')->get();

        $fileName = 'revenue_report_' . $year . ($month ? '_' . $month : '') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'Payment ID',
                'Booking Code',
                'Room Name',
                'Guest Name',
                'Guest Email',
                'Check-in',
                'Check-out',
                'Payment Amount',
                'Payment Method',
                'Payment Date',
                'Verified Date',
                'Status'
            ]);

            // Data rows
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->booking->booking_code,
                    $payment->booking->room->name ?? 'N/A',
                    $payment->booking->user->name ?? 'N/A',
                    $payment->booking->user->email ?? 'N/A',
                    $payment->booking->check_in ? $payment->booking->check_in->format('Y-m-d') : 'N/A',
                    $payment->booking->check_out ? $payment->booking->check_out->format('Y-m-d') : 'N/A',
                    $payment->amount,
                    ucfirst(str_replace('_', ' ', $payment->payment_method)),
                    $payment->created_at->format('Y-m-d H:i:s'),
                    $payment->verified_at ? $payment->verified_at->format('Y-m-d H:i:s') : 'N/A',
                    ucfirst($payment->status)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show all payments
     */
    public function payments(Request $request)
    {
        $owner = Auth::guard('owner')->user();
        $ownerRooms = Room::where('owner_id', $owner->id)->pluck('id');

        // If owner has no rooms, assign all existing rooms to this owner
        if ($ownerRooms->isEmpty()) {
            Room::whereNull('owner_id')->orWhere('owner_id', 0)->update(['owner_id' => $owner->id]);
            $ownerRooms = Room::where('owner_id', $owner->id)->pluck('id');
        }

        $query = Payment::whereHas('booking', function ($q) use ($ownerRooms) {
            $q->whereIn('room_id', $ownerRooms);
        })->with(['booking.room', 'booking.user', 'verifiedBy']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by booking code or user name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('booking', function ($bookingQuery) use ($search) {
                $bookingQuery->where('booking_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('owner.payments', compact('payments'));
    }

    /**
     * Show payment details
     */
    public function showPayment($id)
    {
        $owner = Auth::guard('owner')->user();
        $ownerRooms = Room::where('owner_id', $owner->id)->pluck('id');

        $payment = Payment::whereHas('booking', function ($q) use ($ownerRooms) {
            $q->whereIn('room_id', $ownerRooms);
        })->with(['booking.room', 'booking.user', 'verifiedBy'])->findOrFail($id);

        return view('owner.payment-detail', compact('payment'));
    }

    /**
     * Show all users who have made bookings
     */
    public function users(Request $request)
    {
        $owner = Auth::guard('owner')->user();
        $ownerRooms = Room::where('owner_id', $owner->id)->pluck('id');

        // If owner has no rooms, assign all existing rooms to this owner
        if ($ownerRooms->isEmpty()) {
            Room::whereNull('owner_id')->orWhere('owner_id', 0)->update(['owner_id' => $owner->id]);
            $ownerRooms = Room::where('owner_id', $owner->id)->pluck('id');
        }

        $query = User::whereHas('bookings', function ($q) use ($ownerRooms) {
            $q->whereIn('room_id', $ownerRooms);
        })->withCount(['bookings' => function ($q) use ($ownerRooms) {
            $q->whereIn('room_id', $ownerRooms);
        }])->with(['bookings' => function ($q) use ($ownerRooms) {
            $q->whereIn('room_id', $ownerRooms)->latest()->take(3);
        }]);

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('bookings_count', 'desc')->paginate(15);

        return view('owner.users', compact('users'));
    }

    /**
     * Show user details
     */
    public function showUser($id)
    {
        $owner = Auth::guard('owner')->user();
        $ownerRooms = Room::where('owner_id', $owner->id)->pluck('id');

        $user = User::whereHas('bookings', function ($q) use ($ownerRooms) {
            $q->whereIn('room_id', $ownerRooms);
        })->with(['bookings' => function ($q) use ($ownerRooms) {
            $q->whereIn('room_id', $ownerRooms)->with(['room', 'payment']);
        }])->findOrFail($id);

        $totalBookings = $user->bookings->count();
        $totalSpent = $user->bookings->whereIn('status', ['confirmed', 'completed'])
            ->sum(function ($booking) {
                return $booking->payment && $booking->payment->status === 'verified'
                    ? $booking->payment->amount : $booking->total_cost;
            });

        return view('owner.user-detail', compact('user', 'totalBookings', 'totalSpent'));
    }
}
