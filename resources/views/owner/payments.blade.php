<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payments Management - Owner Panel</title>
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
                <a href="{{ route('owner.payments') }}" class="nav-item active">
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
                <h1><i class="fas fa-credit-card"></i> Payments Management</h1>
                <p>Monitor and track all payment transactions across your property.</p>
            </header>

            <!-- Payment Statistics -->
            <div class="dashboard-grid">
                <div class="dashboard-card payment-card">
                    <div class="card-header">
                        <i class="fas fa-credit-card"></i>
                        <h3>Total Payments</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">{{ $payments->total() ?? 0 }}</div>
                        <div class="stat-label">All time payments</div>
                        <div class="stat-trend neutral">
                            <i class="fas fa-chart-line"></i>
                            Transaction records
                        </div>
                    </div>
                </div>

                <div class="dashboard-card success-card">
                    <div class="card-header">
                        <i class="fas fa-check-circle"></i>
                        <h3>Verified</h3>
                    </div>
                    <div class="card-content">
                        @php
                            $verifiedCount = $payments->where('status', 'verified')->count();
                        @endphp
                        <div class="stat-number">{{ $verifiedCount }}</div>
                        <div class="stat-label">Verified payments</div>
                        <div class="stat-trend positive">
                            <i class="fas fa-arrow-up"></i>
                            Payment success
                        </div>
                    </div>
                </div>

                <div class="dashboard-card warning-card">
                    <div class="card-header">
                        <i class="fas fa-clock"></i>
                        <h3>Pending</h3>
                    </div>
                    <div class="card-content">
                        @php
                            $pendingCount = $payments->where('status', 'pending')->count();
                        @endphp
                        <div class="stat-number">{{ $pendingCount }}</div>
                        <div class="stat-label">Awaiting verification</div>
                        <div class="stat-trend neutral">
                            <i class="fas fa-hourglass-half"></i>
                            Needs attention
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Section -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-filter"></i>
                    <h3>Filter & Search</h3>
                </div>
                <div class="card-content">
                    <form method="GET" action="{{ route('owner.payments') }}" class="filter-form">
                        <div class="filter-grid">
                            <div class="filter-item">
                                <label for="search">Search</label>
                                <input type="text" id="search" name="search" class="form-input"
                                       value="{{ request('search') }}" 
                                       placeholder="Booking code, user name, email...">
                            </div>
                            
                            <div class="filter-item">
                                <label for="status">Status</label>
                                <select id="status" name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>

                            <div class="filter-item">
                                <label for="payment_method">Payment Method</label>
                                <select id="payment_method" name="payment_method" class="form-select">
                                    <option value="">All Methods</option>
                                    <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                                    <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                </select>
                            </div>

                            <div class="filter-item">
                                <label for="date_from">Date From</label>
                                <input type="date" id="date_from" name="date_from" class="form-input" value="{{ request('date_from') }}">
                            </div>

                            <div class="filter-item">
                                <label for="date_to">Date To</label>
                                <input type="date" id="date_to" name="date_to" class="form-input" value="{{ request('date_to') }}">
                            </div>
                        </div>
                        
                        <div class="filter-actions">
                            <button type="submit" class="action-btn primary">
                                <i class="fas fa-search"></i>
                                Apply Filters
                            </button>
                            <a href="{{ route('owner.payments') }}" class="action-btn outline">
                                <i class="fas fa-undo"></i>
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payments Table -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-list"></i>
                    <h3>All Payments</h3>
                    <div class="card-actions">
                        <span class="badge">{{ $payments->total() ?? 0 }} total</span>
                    </div>
                </div>
                <div class="card-content">
                    @if(isset($payments) && $payments->count() > 0)
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Booking</th>
                                        <th>Customer</th>
                                        <th>Room</th>
                                        <th>Amount</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                    <tr>
                                        <td>
                                            <div class="booking-code">{{ $payment->booking->booking_code }}</div>
                                        </td>
                                        <td>
                                            <div class="guest-info">
                                                <div class="guest-name">{{ $payment->booking->user->name }}</div>
                                                <div class="guest-email">{{ $payment->booking->user->email }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="room-name">{{ $payment->booking->room->name }}</div>
                                        </td>
                                        <td class="amount-cell">
                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <div class="payment-method">
                                                @if($payment->payment_method == 'qris')
                                                    <i class="fas fa-qrcode"></i> QRIS
                                                @else
                                                    <i class="fas fa-university"></i> Bank Transfer
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ $payment->status }}">
                                                @switch($payment->status)
                                                    @case('pending')
                                                        <i class="fas fa-clock"></i> Pending
                                                        @break
                                                    @case('verified')
                                                        <i class="fas fa-check-circle"></i> Verified
                                                        @break
                                                    @case('rejected')
                                                        <i class="fas fa-times-circle"></i> Rejected
                                                        @break
                                                @endswitch
                                            </span>
                                        </td>
                                        <td>
                                            <div class="date-info">
                                                <strong>{{ $payment->created_at ? $payment->created_at->format('d M Y') : 'N/A' }}</strong>
                                                <small>{{ $payment->created_at ? $payment->created_at->format('H:i') : '' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons-inline">
                                                <a href="{{ route('owner.payments.show', $payment->id) }}" 
                                                   class="btn-small btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                    Details
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($payments->hasPages())
                            <div class="pagination-wrapper">
                                {{ $payments->appends(request()->query())->links() }}
                            </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <i class="fas fa-credit-card"></i>
                            <h4>No Payments Found</h4>
                            <p>
                                @if(request()->hasAny(['search', 'status', 'payment_method', 'date_from', 'date_to']))
                                    No payment transactions match your current filters. Try adjusting your search criteria.
                                @else
                                    No payment transactions have been made yet. They will appear here once customers start paying.
                                @endif
                            </p>
                            @if(request()->hasAny(['search', 'status', 'payment_method', 'date_from', 'date_to']))
                                <a href="{{ route('owner.payments') }}" class="action-btn outline">
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
                        <a href="{{ route('owner.bookings') }}" class="action-btn primary">
                            <i class="fas fa-calendar-check"></i>
                            View Bookings
                        </a>
                        <a href="{{ route('owner.revenue') }}" class="action-btn success">
                            <i class="fas fa-chart-line"></i>
                            Revenue Report
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
