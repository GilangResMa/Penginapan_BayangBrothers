<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Customer Management - Owner Panel</title>
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
                <a href="{{ route('owner.bookings') }}" class="nav-item">
                    <i class="fas fa-calendar-check"></i>
                    Bookings
                </a>
                <a href="{{ route('owner.payments') }}" class="nav-item">
                    <i class="fas fa-credit-card"></i>
                    Payments
                </a>
                <a href="{{ route('owner.users') }}" class="nav-item active">
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
                <h1><i class="fas fa-users"></i> Customer Management</h1>
                <p>View and manage all customers who have made bookings across your property.</p>
            </header>

            <!-- Customer Statistics -->
            <div class="dashboard-grid">
                <div class="dashboard-card customer-card">
                    <div class="card-header">
                        <i class="fas fa-users"></i>
                        <h3>Total Customers</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">{{ $users->total() ?? 0 }}</div>
                        <div class="stat-label">Registered customers</div>
                        <div class="stat-trend neutral">
                            <i class="fas fa-user-plus"></i>
                            Customer base
                        </div>
                    </div>
                </div>

                <div class="dashboard-card success-card">
                    <div class="card-header">
                        <i class="fas fa-star"></i>
                        <h3>Repeat Customers</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">{{ $users->where('bookings_count', '>', 1)->count() }}</div>
                        <div class="stat-label">Loyal customers</div>
                        <div class="stat-trend positive">
                            <i class="fas fa-heart"></i>
                            Customer loyalty
                        </div>
                    </div>
                </div>

                <div class="dashboard-card booking-card">
                    <div class="card-header">
                        <i class="fas fa-calendar-check"></i>
                        <h3>Total Bookings</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">{{ number_format($users->sum('bookings_count')) }}</div>
                        <div class="stat-label">All time bookings</div>
                        <div class="stat-trend positive">
                            <i class="fas fa-chart-line"></i>
                            Business activity
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Section -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-search"></i>
                    <h3>Search Customers</h3>
                </div>
                <div class="card-content">
                    <form method="GET" action="{{ route('owner.users') }}" class="filter-form">
                        <div class="filter-grid">
                            <div class="filter-item">
                                <label for="search">Search</label>
                                <input type="text" id="search" name="search" class="form-input"
                                       value="{{ request('search') }}" 
                                       placeholder="Customer name or email...">
                            </div>
                        </div>
                        
                        <div class="filter-actions">
                            <button type="submit" class="action-btn primary">
                                <i class="fas fa-search"></i>
                                Search
                            </button>
                            <a href="{{ route('owner.users') }}" class="action-btn outline">
                                <i class="fas fa-undo"></i>
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Customers Table -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-list"></i>
                    <h3>All Customers</h3>
                    <div class="card-actions">
                        <span class="badge">{{ $users->total() ?? 0 }} total</span>
                    </div>
                </div>
                <div class="card-content">
                    @if(isset($users) && $users->count() > 0)
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Email</th>
                                        <th>Total Bookings</th>
                                        <th>Member Since</th>
                                        <th>Recent Activity</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <div class="guest-info">
                                                <div class="user-avatar">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                                <div class="user-details">
                                                    <div class="guest-name">{{ $user->name }}</div>
                                                    @if($user->bookings_count > 1)
                                                        <span class="badge success">
                                                            <i class="fas fa-star"></i>
                                                            Repeat Customer
                                                        </span>
                                                    @else
                                                        <span class="badge secondary">New Customer</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="guest-email">{{ $user->email }}</div>
                                        </td>
                                        <td>
                                            <div class="booking-count-display">
                                                <span class="booking-count">{{ $user->bookings_count }}</span>
                                                <small class="text-muted">bookings</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="date-info">
                                                <strong>{{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}</strong>
                                                <small>{{ $user->created_at ? $user->created_at->diffForHumans() : 'Unknown' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="recent-bookings-display">
                                                @forelse($user->bookings->take(2) as $booking)
                                                    <div class="booking-item-display">
                                                        <div class="booking-code">{{ $booking->booking_code }}</div>
                                                        <div class="booking-details">
                                                            <span class="booking-date">{{ $booking->created_at ? $booking->created_at->format('M Y') : 'N/A' }}</span>
                                                            <span class="status-badge status-{{ $booking->status }}">
                                                                {{ ucfirst($booking->status) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <span class="text-muted">No bookings</span>
                                                @endforelse
                                                @if($user->bookings->count() > 2)
                                                    <small class="text-muted">+{{ $user->bookings->count() - 2 }} more bookings</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons-inline">
                                                <a href="{{ route('owner.users.show', $user->id) }}" 
                                                   class="btn-small btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                    Profile
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($users->hasPages())
                            <div class="pagination-wrapper">
                                {{ $users->appends(request()->query())->links() }}
                            </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <h4>No Customers Found</h4>
                            <p>
                                @if(request()->has('search'))
                                    No customers match your search criteria. Try adjusting your search terms.
                                @else
                                    No customers have made bookings yet. They will appear here once customers start booking.
                                @endif
                            </p>
                            @if(request()->has('search'))
                                <a href="{{ route('owner.users') }}" class="action-btn outline">
                                    <i class="fas fa-undo"></i>
                                    Clear Search
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
                        <a href="{{ route('owner.bookings') }}" class="action-btn primary">
                            <i class="fas fa-calendar-check"></i>
                            View Bookings
                        </a>
                        <a href="{{ route('owner.payments') }}" class="action-btn success">
                            <i class="fas fa-credit-card"></i>
                            View Payments
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

    <script>
        // Mobile menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
            const sidebar = document.querySelector('.sidebar');

            if (mobileMenuToggle && mobileMenuOverlay && sidebar) {
                mobileMenuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    mobileMenuOverlay.classList.toggle('active');
                    this.classList.toggle('active');
                });

                mobileMenuOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    this.classList.remove('active');
                    mobileMenuToggle.classList.remove('active');
                });

                // Close mobile menu when window resizes to desktop size
                window.addEventListener('resize', function() {
                    if (window.innerWidth > 768) {
                        sidebar.classList.remove('active');
                        mobileMenuOverlay.classList.remove('active');
                        mobileMenuToggle.classList.remove('active');
                    }
                });
            }
        });
    </script>
</body>

</html>
