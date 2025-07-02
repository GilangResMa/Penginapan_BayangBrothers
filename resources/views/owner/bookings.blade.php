<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bookings Management - Owner Panel - Bayang Brothers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/owner.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
</head>

<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-crown logo-icon"></i>
                <h2>Owner Panel</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('owner.dashboard') }}" class="nav-item">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
                <a href="{{ route('owner.bookings') }}" class="nav-item active">
                    <i class="fas fa-calendar-check"></i>
                    Bookings
                </a>
                <a href="{{ route('owner.payments') }}" class="nav-item">
                    <i class="fas fa-credit-card"></i>
                    Payments
                </a>
                <a href="{{ route('owner.users') }}" class="nav-item">
                    <i class="fas fa-users"></i>
                    Customers
                </a>
                <a href="{{ route('owner.revenue') }}" class="nav-item">
                    <i class="fas fa-chart-line"></i>
                    Revenue Report
                </a>
                <a href="{{ route('owner.admins') }}" class="nav-item">
                    <i class="fas fa-user-shield"></i>
                    Admin Management
                </a>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="nav-item logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="content-header">
                <h1><i class="fas fa-calendar-check"></i> Bookings Management</h1>
                <p>Monitor and manage all customer bookings across your property.</p>
            </header>

            <!-- Filter & Search Section -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-filter"></i>
                    <h3>Filter & Search</h3>
                </div>
                <div class="card-content">
                    <form method="GET" action="{{ route('owner.bookings') }}" class="filter-form">
                        <div class="filter-grid">
                            <div class="filter-item">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            
                            <div class="filter-item">
                                <label for="date_from">From Date</label>
                                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="form-input">
                            </div>
                            
                            <div class="filter-item">
                                <label for="date_to">To Date</label>
                                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="form-input">
                            </div>
                            
                            <div class="filter-item">
                                <label for="search">Search</label>
                                <input type="text" name="search" id="search" placeholder="Booking code, guest name..." value="{{ request('search') }}" class="form-input">
                            </div>
                        </div>
                        
                        <div class="filter-actions">
                            <button type="submit" class="action-btn primary">
                                <i class="fas fa-search"></i>
                                Apply Filters
                            </button>
                            <a href="{{ route('owner.bookings') }}" class="action-btn outline">
                                <i class="fas fa-undo"></i>
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Booking Statistics -->
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-clock"></i>
                        <h3>Pending</h3>
                    </div>
                    <div class="card-content">
                        @php
                            $pendingCount = $bookings->where('status', 'pending')->count();
                        @endphp
                        <div class="stat-number">{{ $pendingCount }}</div>
                        <div class="stat-label">Awaiting confirmation</div>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-check-circle"></i>
                        <h3>Confirmed</h3>
                    </div>
                    <div class="card-content">
                        @php
                            $confirmedCount = $bookings->where('status', 'confirmed')->count();
                        @endphp
                        <div class="stat-number">{{ $confirmedCount }}</div>
                        <div class="stat-label">Active bookings</div>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-star"></i>
                        <h3>Completed</h3>
                    </div>
                    <div class="card-content">
                        @php
                            $completedCount = $bookings->where('status', 'completed')->count();
                        @endphp
                        <div class="stat-number">{{ $completedCount }}</div>
                        <div class="stat-label">Finished stays</div>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-times-circle"></i>
                        <h3>Cancelled</h3>
                    </div>
                    <div class="card-content">
                        @php
                            $cancelledCount = $bookings->where('status', 'cancelled')->count();
                        @endphp
                        <div class="stat-number">{{ $cancelledCount }}</div>
                        <div class="stat-label">Cancelled bookings</div>
                    </div>
                </div>
            </div>

            <!-- Bookings Table -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-list"></i>
                    <h3>All Bookings</h3>
                    <div class="card-actions">
                        <span class="badge">{{ $bookings->total() ?? 0 }} total</span>
                    </div>
                </div>
                <div class="card-content">
                    @if(isset($bookings) && $bookings->count() > 0)
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Booking Code</th>
                                        <th>Guest</th>
                                        <th>Room</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                    <tr>
                                        <td>
                                            <div class="booking-code">{{ $booking->booking_code }}</div>
                                            <div class="booking-date">{{ $booking->created_at ? $booking->created_at->format('d M Y, H:i') : 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <div class="guest-info">
                                                <div class="guest-name">{{ $booking->user->name }}</div>
                                                <div class="guest-email">{{ $booking->user->email }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="room-info">
                                                <div class="room-name">{{ $booking->room->name }}</div>
                                                <div class="room-details">
                                                    {{ $booking->guests }} guests
                                                    @if($booking->extra_bed)
                                                        <span class="extra-bed-tag">+ Extra Bed</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="date-cell">{{ $booking->check_in ? $booking->check_in->format('d M Y') : 'N/A' }}</td>
                                        <td class="date-cell">{{ $booking->check_out ? $booking->check_out->format('d M Y') : 'N/A' }}</td>
                                        <td class="amount-cell">Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="status-badge status-{{ $booking->status }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($booking->payment_method)
                                                <div class="payment-info">
                                                    <div class="payment-method">{{ ucfirst(str_replace('_', ' ', $booking->payment_method)) }}</div>
                                                    @if($booking->payment_confirmed_at)
                                                        <div class="payment-confirmed">
                                                            <i class="fas fa-check-circle"></i>
                                                            Confirmed
                                                        </div>
                                                    @else
                                                        <div class="payment-pending">
                                                            <i class="fas fa-clock"></i>
                                                            Pending
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">Not paid</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-buttons-inline">
                                                <a href="{{ route('owner.booking.show', $booking->id) }}" class="btn-small btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                    Details
                                                </a>
                                                @if($booking->payment)
                                                    <a href="{{ route('owner.payments.show', $booking->payment->id) }}" class="btn-small btn-secondary" title="View Payment">
                                                        <i class="fas fa-credit-card"></i>
                                                        Payment
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($bookings->hasPages())
                            <div class="pagination-wrapper">
                                {{ $bookings->links() }}
                            </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <i class="fas fa-calendar-times"></i>
                            <h4>No Bookings Found</h4>
                            <p>
                                @if(request()->hasAny(['status', 'date_from', 'date_to', 'search']))
                                    No bookings match your current filters. Try adjusting your search criteria.
                                @else
                                    No bookings have been made yet. They will appear here once customers start booking.
                                @endif
                            </p>
                            @if(request()->hasAny(['status', 'date_from', 'date_to', 'search']))
                                <a href="{{ route('owner.bookings') }}" class="action-btn outline">
                                    <i class="fas fa-undo"></i>
                                    Clear Filters
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-lightning-bolt"></i>
                    <h3>Quick Actions</h3>
                </div>
                <div class="card-content">
                    <div class="action-buttons">
                        <a href="{{ route('owner.dashboard') }}" class="action-btn secondary">
                            <i class="fas fa-tachometer-alt"></i>
                            Back to Dashboard
                        </a>
                        <a href="{{ route('owner.revenue') }}" class="action-btn primary">
                            <i class="fas fa-chart-line"></i>
                            View Revenue Report
                        </a>
                        <button onclick="window.print()" class="action-btn outline">
                            <i class="fas fa-print"></i>
                            Print Report
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @if(session('success'))
        <div class="alert alert-success" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif
</body>

</html>
