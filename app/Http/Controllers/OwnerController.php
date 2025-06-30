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
        
        // Get owner's rooms first
        $ownerRooms = Room::where('owner_id', $owner->id)->pluck('id');
        
        // Statistics based on owner's rooms
        $totalBookings = Booking::whereIn('room_id', $ownerRooms)->count();
        $totalRevenue = Booking::whereIn('room_id', $ownerRooms)
                             ->where('status', 'confirmed')
                             ->sum('total_cost');
        
        // Monthly revenue for current month
        $monthlyRevenue = Booking::whereIn('room_id', $ownerRooms)
                               ->where('status', 'confirmed')
                               ->whereMonth('created_at', now()->month)
                               ->whereYear('created_at', now()->year)
                               ->sum('total_cost');
        
        $totalAdmins = Admin::where('created_by', $owner->id)->count();
        $totalRooms = $ownerRooms->count();

        // Recent bookings
        $recentBookings = Booking::whereIn('room_id', $ownerRooms)
            ->with(['room', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Monthly revenue chart data for the last 12 months
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenue = Booking::whereIn('room_id', $ownerRooms)
                            ->where('status', 'confirmed')
                            ->whereYear('created_at', $date->year)
                            ->whereMonth('created_at', $date->month)
                            ->sum('total_cost');
            
            $monthlyData[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue
            ];
        }

        // Booking status statistics
        $bookingStats = [
            'total' => $totalBookings,
            'confirmed' => Booking::whereIn('room_id', $ownerRooms)->where('status', 'confirmed')->count(),
            'pending' => Booking::whereIn('room_id', $ownerRooms)->where('status', 'pending')->count(),
            'cancelled' => Booking::whereIn('room_id', $ownerRooms)->where('status', 'cancelled')->count(),
        ];
        
        $bookingStats['success_rate'] = $bookingStats['total'] > 0 
            ? round(($bookingStats['confirmed'] / $bookingStats['total']) * 100, 2) 
            : 0;

        return view('owner.dashboard', compact(
            'owner',
            'totalBookings',
            'totalRevenue',
            'monthlyRevenue',
            'totalAdmins',
            'totalRooms',
            'recentBookings',
            'monthlyData',
            'bookingStats'
        ));
    }

    /**
     * Show all bookings
     */
    public function bookings(Request $request)
    {
        $owner = Auth::guard('owner')->user();
        $ownerRooms = Room::where('owner_id', $owner->id)->pluck('id');
        
        $query = Booking::whereIn('room_id', $ownerRooms)->with(['room', 'user']);

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
        
        $booking = Booking::whereIn('room_id', $ownerRooms)
                         ->with(['room', 'user'])
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
        
        // Default to current year
        $year = $request->get('year', date('Y'));
        $month = $request->get('month');

        // Monthly revenue for the selected year
        $monthlyRevenue = [];
        for ($i = 1; $i <= 12; $i++) {
            $revenue = Booking::whereIn('room_id', $ownerRooms)
                            ->where('status', 'confirmed')
                            ->whereYear('created_at', $year)
                            ->whereMonth('created_at', $i)
                            ->sum('total_cost');
            
            $monthlyRevenue[] = [
                'month' => Carbon::create($year, $i, 1)->format('M'),
                'revenue' => $revenue
            ];
        }

        // Top performing rooms
        $topRooms = Room::where('owner_id', $owner->id)
            ->select('rooms.*')
            ->selectSub(function ($query) use ($year, $month) {
                $query->from('bookings')
                     ->selectRaw('COALESCE(SUM(total_cost), 0)')
                     ->whereColumn('bookings.room_id', 'rooms.id')
                     ->where('status', 'confirmed');
                
                if ($year) {
                    $query->whereYear('created_at', $year);
                }
                if ($month) {
                    $query->whereMonth('created_at', $month);
                }
            }, 'total_revenue')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get();

        // Year-over-year comparison
        $currentYearRevenue = Booking::whereIn('room_id', $ownerRooms)
            ->where('status', 'confirmed')
            ->whereYear('created_at', $year)
            ->sum('total_cost');

        $previousYearRevenue = Booking::whereIn('room_id', $ownerRooms)
            ->where('status', 'confirmed')
            ->whereYear('created_at', $year - 1)
            ->sum('total_cost');

        $revenueGrowth = $previousYearRevenue > 0 
            ? (($currentYearRevenue - $previousYearRevenue) / $previousYearRevenue) * 100 
            : 0;

        // Available years for filter
        $availableYears = Booking::whereIn('room_id', $ownerRooms)
            ->selectRaw('YEAR(created_at) as year')
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

        $query = Booking::whereIn('room_id', $ownerRooms)
                       ->where('status', 'confirmed')
                       ->with(['room', 'user']);

        if ($year) {
            $query->whereYear('created_at', $year);
        }

        if ($month) {
            $query->whereMonth('created_at', $month);
        }

        $bookings = $query->orderBy('created_at', 'desc')->get();

        $fileName = 'revenue_report_' . $year . ($month ? '_' . $month : '') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'Booking Code',
                'Room Name',
                'Guest Name',
                'Guest Email',
                'Check-in',
                'Check-out',
                'Total Cost',
                'Booking Date',
                'Status'
            ]);

            // Data rows
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->booking_code,
                    $booking->room->name ?? 'N/A',
                    $booking->user->name ?? 'N/A',
                    $booking->user->email ?? 'N/A',
                    $booking->check_in ? $booking->check_in->format('Y-m-d') : 'N/A',
                    $booking->check_out ? $booking->check_out->format('Y-m-d') : 'N/A',
                    $booking->total_cost,
                    $booking->created_at->format('Y-m-d H:i:s'),
                    ucfirst($booking->status)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
