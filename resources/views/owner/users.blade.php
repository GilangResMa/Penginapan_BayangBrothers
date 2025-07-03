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
                        <div class="search-row">
                            <div class="search-input-container">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" id="search" name="search" class="search-input"
                                       value="{{ request('search') }}" 
                                       placeholder="Search customer name, email, phone number...">
                            </div>
                            <div class="search-actions">
                                <button type="submit" class="search-btn">
                                    Search
                                </button>
                                @if(request()->has('search'))
                                    <a href="{{ route('owner.users') }}" class="clear-search-btn">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                        
                        <div class="filter-options">
                            <div class="filter-label-text">Advanced filters coming soon:</div>
                            <div class="filter-chips">
                                <span class="filter-chip disabled">
                                    <i class="fas fa-star"></i> Loyal Customers
                                </span>
                                <span class="filter-chip disabled">
                                    <i class="fas fa-calendar-check"></i> With Bookings
                                </span>
                                <span class="filter-chip disabled">
                                    <i class="fas fa-calendar-times"></i> No Bookings
                                </span>
                            </div>
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
                        <div class="dropdown">
                            <button class="dropdown-toggle">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a href="#" class="dropdown-item disabled">
                                    <i class="fas fa-file-export"></i> Export CSV
                                </a>
                                <a href="#" class="dropdown-item disabled">
                                    <i class="fas fa-envelope"></i> Email All
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-content">
                    @if(isset($users) && $users->count() > 0)
                        <div class="table-responsive">
                            <table class="admin-table customers-table">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Contact Info</th>
                                        <th>Bookings</th>
                                        <th>Joined</th>
                                        <th>Recent Activity</th>
                                        <th class="col-actions"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <div class="guest-info">
                                                <div class="user-avatar {{ $user->bookings_count > 1 ? 'loyal' : '' }}">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                                <div class="user-details">
                                                    <div class="guest-name">{{ $user->name }}</div>
                                                    <div class="customer-tags">
                                                        @if($user->bookings_count > 1)
                                                            <span class="badge success">
                                                                <i class="fas fa-star"></i>
                                                                Loyal
                                                            </span>
                                                        @else
                                                            <span class="badge secondary">New</span>
                                                        @endif
                                                        
                                                        @if($user->bookings_count > 3)
                                                            <span class="badge primary">
                                                                <i class="fas fa-award"></i>
                                                                VIP
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="contact-info">
                                                <div class="guest-email">
                                                    <i class="fas fa-envelope"></i>
                                                    {{ $user->email }}
                                                </div>
                                                @if(isset($user->contact) && $user->contact)
                                                    <div class="guest-phone">
                                                        <i class="fas fa-phone"></i>
                                                        {{ $user->contact }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="booking-count-display">
                                                <span class="booking-count">{{ $user->bookings_count }}</span>
                                                <div class="booking-label">
                                                    <span>bookings</span>
                                                    @if($user->bookings_count > 0)
                                                        <div class="booking-value">
                                                            Rp {{ number_format($user->bookings->sum('total_cost'), 0, ',', '.') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="check-date">
                                                <i class="fas fa-calendar-alt"></i> 
                                                {{ $user->created_at ? $user->created_at->format('d/m/y') : 'N/A' }}
                                            </div>
                                            <div class="booking-date-small">
                                                {{ $user->created_at ? $user->created_at->diffForHumans() : 'Unknown' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="recent-bookings-display">
                                                @forelse($user->bookings->sortByDesc('created_at')->take(1) as $booking)
                                                    <div class="booking-item-display">
                                                        <div class="booking-code">{{ $booking->booking_code }}</div>
                                                        <div class="booking-details">
                                                            <span class="booking-date-small">{{ $booking->created_at ? $booking->created_at->format('d/m/y') : 'N/A' }}</span>
                                                            <span class="status-badge status-{{ $booking->status }}">
                                                                <i class="fas fa-{{ 
                                                                    $booking->status == 'pending' ? 'clock' : 
                                                                    ($booking->status == 'confirmed' ? 'check-circle' : 
                                                                    ($booking->status == 'completed' ? 'star' : 'times-circle')) 
                                                                }}"></i>
                                                                {{ ucfirst($booking->status) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <span class="text-muted">No bookings yet</span>
                                                @endforelse
                                                @if($user->bookings->count() > 1)
                                                    <div class="more-bookings">
                                                        <small class="text-muted">+{{ $user->bookings->count() - 1 }} more</small>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('owner.user.show', $user->id) }}" class="action-icon" title="View Customer Profile">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
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
