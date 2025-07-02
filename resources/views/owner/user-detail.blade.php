<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Customer Details</title>
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
                <div class="header-with-back">
                    <div>
                        <h1>Customer Details</h1>
                        <p>Complete profile and booking history</p>
                    </div>
                    <a href="{{ route('owner.users') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back to Customers
                    </a>
                </div>
            </header>

            <!-- Customer Overview -->
            <div class="customer-overview">
                <div class="customer-header">
                    <div class="customer-avatar-large">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div class="customer-basic-info">
                        <h2>{{ $user->name }}</h2>
                        <p class="email">{{ $user->email }}</p>
                        <div class="customer-badges">
                            @if($totalBookings > 1)
                                <span class="badge badge-success">Repeat Customer</span>
                            @endif
                            @if($totalBookings > 5)
                                <span class="badge badge-gold">VIP Customer</span>
                            @endif
                            <span class="badge badge-info">Member since {{ $user->created_at ? $user->created_at->format('M Y') : 'Unknown' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $totalBookings }}</h3>
                        <p>Total Bookings</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-money-bill"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Rp {{ number_format($totalSpent, 0, ',', '.') }}</h3>
                        <p>Total Spent</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $user->bookings->first() ? $user->bookings->first()->created_at->diffForHumans() : 'Never' }}</h3>
                        <p>Last Booking</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $totalBookings > 0 ? number_format($totalSpent / $totalBookings, 0, ',', '.') : '0' }}</h3>
                        <p>Avg. Booking Value</p>
                    </div>
                </div>
            </div>

            <!-- Booking History -->
            <div class="section-card">
                <div class="section-header">
                    <h3><i class="fas fa-history"></i> Booking History</h3>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Booking Code</th>
                                <th>Room</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Duration</th>
                                <th>Total Cost</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->bookings as $booking)
                            <tr>
                                <td>
                                    <span class="booking-code">{{ $booking->booking_code }}</span>
                                </td>
                                <td>{{ $booking->room->name }}</td>
                                <td>{{ $booking->check_in ? $booking->check_in->format('d M Y') : 'N/A' }}</td>
                                <td>{{ $booking->check_out ? $booking->check_out->format('d M Y') : 'N/A' }}</td>
                                <td>{{ $booking->check_in && $booking->check_out ? $booking->check_in->diffInDays($booking->check_out) : 0 }} nights</td>
                                <td>
                                    <span class="amount">Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $booking->status }}">
                                        @switch($booking->status)
                                            @case('pending')
                                                <i class="fas fa-clock"></i> Pending
                                                @break
                                            @case('awaiting_payment')
                                                <i class="fas fa-credit-card"></i> Awaiting Payment
                                                @break
                                            @case('confirmed')
                                                <i class="fas fa-check-circle"></i> Confirmed
                                                @break
                                            @case('cancelled')
                                                <i class="fas fa-times-circle"></i> Cancelled
                                                @break
                                            @case('completed')
                                                <i class="fas fa-check-double"></i> Completed
                                                @break
                                            @default
                                                {{ ucfirst($booking->status) }}
                                        @endswitch
                                    </span>
                                </td>
                                <td>
                                    @if($booking->payment)
                                        <div class="payment-info">
                                            <span class="status-badge status-{{ $booking->payment->status }}">
                                                @switch($booking->payment->status)
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
                                            <small class="payment-method">
                                                @if($booking->payment->payment_method == 'qris')
                                                    <i class="fas fa-qrcode"></i> QRIS
                                                @else
                                                    <i class="fas fa-university"></i> Bank Transfer
                                                @endif
                                            </small>
                                        </div>
                                    @else
                                        <span class="text-muted">No payment</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="date-info">
                                        <strong>{{ $booking->created_at ? $booking->created_at->format('d M Y') : 'N/A' }}</strong>
                                        <small>{{ $booking->created_at ? $booking->created_at->format('H:i') : '' }}</small>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">
                                    <div class="empty-state">
                                        <i class="fas fa-calendar-times"></i>
                                        <h3>No Bookings Found</h3>
                                        <p>This customer hasn't made any bookings yet.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <style>
        .header-with-back {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .customer-overview {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .customer-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .customer-avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #3B82F6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.8rem;
        }

        .customer-basic-info h2 {
            margin: 0 0 0.5rem 0;
            color: #1F2937;
        }

        .customer-basic-info .email {
            margin: 0 0 1rem 0;
            color: #6B7280;
            font-size: 1.1rem;
        }

        .customer-badges {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 16px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .badge-success {
            background: #DEF7EC;
            color: #03543F;
        }

        .badge-gold {
            background: #FEF3C7;
            color: #92400E;
        }

        .badge-info {
            background: #DBEAFE;
            color: #1E40AF;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #3B82F6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-info h3 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
            color: #1F2937;
        }

        .stat-info p {
            margin: 0;
            color: #6B7280;
            font-size: 0.9rem;
        }

        .section-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .section-header {
            background: #F8F9FA;
            padding: 1.5rem;
            border-bottom: 1px solid #E5E7EB;
        }

        .section-header h3 {
            margin: 0;
            color: #374151;
            font-size: 1.2rem;
        }

        .booking-code {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            color: #374151;
        }

        .amount {
            font-weight: 600;
            color: #059669;
        }

        .payment-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .payment-method {
            color: #6B7280;
            font-size: 0.8rem;
        }

        .date-info strong {
            display: block;
        }

        .date-info small {
            color: #6B7280;
        }

        .text-muted {
            color: #9CA3AF;
            font-style: italic;
        }
    </style>

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
