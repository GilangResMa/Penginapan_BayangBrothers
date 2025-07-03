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
        <aside class="sidebar" id="sidebar">
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

        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle" id="mobileMenuToggle">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>

        <!-- Mobile Menu Overlay -->
        <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
        
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
                        <div class="search-row">
                            <div class="search-input-container">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" name="search" id="search" placeholder="Search booking code, guest name, email..." value="{{ request('search') }}" class="search-input">
                            </div>
                        </div>
                        
                        <div class="filter-grid">
                            <div class="filter-item">
                                <label for="status" class="filter-label">
                                    <i class="fas fa-tag"></i>
                                    Booking Status
                                </label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                        <i class="fas fa-clock"></i> Pending
                                    </option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>
                                        <i class="fas fa-check-circle"></i> Confirmed
                                    </option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                        <i class="fas fa-star"></i> Completed
                                    </option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                        <i class="fas fa-times-circle"></i> Cancelled
                                    </option>
                                </select>
                            </div>
                            
                            <div class="filter-item">
                                <label for="date_from" class="filter-label">
                                    <i class="fas fa-calendar-alt"></i>
                                    Check-In From
                                </label>
                                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="form-input">
                            </div>
                            
                            <div class="filter-item">
                                <label for="date_to" class="filter-label">
                                    <i class="fas fa-calendar-alt"></i>
                                    Check-In To
                                </label>
                                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="form-input">
                            </div>
                        </div>
                        
                        <div class="filter-actions">
                            <button type="submit" class="action-btn primary">
                                <i class="fas fa-search"></i>
                                Apply Filters
                            </button>
                            <a href="{{ route('owner.bookings') }}" class="action-btn outline">
                                <i class="fas fa-undo"></i>
                                Clear All Filters
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
                                        <th class="col-code">Booking</th>
                                        <th class="col-guest">Guest</th>
                                        <th class="col-room">Room</th>
                                        <th class="col-dates">Dates</th>
                                        <th class="col-amount">Amount</th>
                                        <th class="col-status">Status</th>
                                        <th class="col-payment">Payment</th>
                                        <th class="col-actions"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                    <tr>
                                        <td>
                                            <div class="booking-code">{{ $booking->booking_code }}</div>
                                            <div class="booking-date-small">{{ $booking->created_at ? $booking->created_at->format('d/m/y') : 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <div class="guest-name">{{ $booking->user->name }}</div>
                                            <div class="guest-email">{{ $booking->user->email }}</div>
                                        </td>
                                        <td>
                                            <div class="room-name">{{ $booking->room->name }}</div>
                                            <div class="room-occupancy-small">
                                                {{ $booking->guests }} guests
                                                @if($booking->extra_bed)
                                                    <span>+ Extra Bed</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="booking-dates">
                                                <div class="check-date"><i class="fas fa-sign-in-alt"></i> {{ $booking->check_in ? $booking->check_in->format('d/m/y') : 'N/A' }}</div>
                                                <div class="check-date"><i class="fas fa-sign-out-alt"></i> {{ $booking->check_out ? $booking->check_out->format('d/m/y') : 'N/A' }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="amount">Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</div>
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ $booking->status }}">
                                                <i class="fas fa-{{ 
                                                    $booking->status == 'pending' ? 'clock' : 
                                                    ($booking->status == 'confirmed' ? 'check-circle' : 
                                                    ($booking->status == 'completed' ? 'star' : 'times-circle')) 
                                                }}"></i>
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $payment = null;
                                                if ($booking->payments !== null) {
                                                    $payment = $booking->payments->first();
                                                }
                                            @endphp

                                            @if($payment)
                                                <span class="status-badge status-{{ $payment->status }}">
                                                    <i class="fas fa-{{ 
                                                        $payment->status == 'pending' ? 'clock' : 
                                                        ($payment->status == 'verified' ? 'check-circle' : 'times-circle') 
                                                    }}"></i>
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            @else
                                                <span class="status-badge status-pending">
                                                    <i class="fas fa-hourglass"></i>
                                                    Not Paid
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('owner.booking.show', $booking->id) }}" class="action-icon" title="View Details">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
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

    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            document.getElementById('sidebar').classList.toggle('active');
            document.querySelector('.mobile-menu-overlay').classList.toggle('active');
            document.body.classList.toggle('menu-open');
        }
        
        document.getElementById('mobileMenuToggle').addEventListener('click', toggleMobileMenu);
        document.querySelector('.mobile-menu-overlay').addEventListener('click', toggleMobileMenu);
    </script>
</body>

</html>
