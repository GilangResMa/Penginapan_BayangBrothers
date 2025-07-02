<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Owner Dashboard - Bayang Brothers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/owner.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <a href="{{ route('owner.dashboard') }}" class="nav-item active">
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
                <h1>Owner Dashboard</h1>
                <p>Welcome back, {{ $owner->name }}! Here's your business overview.</p>
                @if($totalRooms == 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        No rooms assigned to your account. Contact administrator to assign rooms.
                    </div>
                @endif
            </header>

            <!-- Statistics Cards -->
            <div class="dashboard-grid">
                <!-- Total Revenue -->
                <div class="dashboard-card revenue-card">
                    <div class="card-header">
                        <i class="fas fa-dollar-sign"></i>
                        <h3>Total Revenue</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                        <div class="stat-label">
                            @if($verifiedPayments > 0)
                                From verified payments
                            @else
                                From confirmed bookings
                            @endif
                        </div>
                        <div class="stat-trend positive">
                            <i class="fas fa-arrow-up"></i>
                            This month: Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}
                        </div>
                        <a href="{{ route('owner.revenue') }}" class="card-action">
                            View Revenue Report <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Total Bookings -->
                <div class="dashboard-card booking-card">
                    <div class="card-header">
                        <i class="fas fa-calendar-check"></i>
                        <h3>Bookings</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">{{ $totalBookings }}</div>
                        <div class="stat-label">Total Bookings</div>
                        <div class="stat-trend {{ $bookingStats['success_rate'] > 80 ? 'positive' : 'neutral' }}">
                            <i class="fas fa-percentage"></i>
                            Success Rate: {{ $bookingStats['success_rate'] }}%
                        </div>
                        <a href="{{ route('owner.bookings') }}" class="card-action">
                            Manage Bookings <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Total Rooms -->
                <div class="dashboard-card room-card">
                    <div class="card-header">
                        <i class="fas fa-bed"></i>
                        <h3>Rooms</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">{{ $totalRooms }}</div>
                        <div class="stat-label">Active Rooms</div>
                        <div class="stat-detail">
                            <small>Managed properties</small>
                        </div>
                    </div>
                </div>

                <!-- Total Admins -->
                <div class="dashboard-card admin-card">
                    <div class="card-header">
                        <i class="fas fa-user-shield"></i>
                        <h3>Admins</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">{{ $totalAdmins }}</div>
                        <div class="stat-label">Active Admins</div>
                        <a href="{{ route('owner.admins') }}" class="card-action">
                            Manage Admins <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Payments Overview -->
                <div class="dashboard-card payment-card">
                    <div class="card-header">
                        <i class="fas fa-credit-card"></i>
                        <h3>Payments</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">{{ $verifiedPayments }}</div>
                        <div class="stat-label">Verified Payments</div>
                        @if($pendingPayments > 0)
                        <div class="stat-trend warning">
                            <i class="fas fa-clock"></i>
                            {{ $pendingPayments }} pending verification
                        </div>
                        @elseif($verifiedPayments == 0 && $totalBookings > 0)
                        <div class="stat-trend info">
                            <i class="fas fa-info-circle"></i>
                            Payment system available for new bookings
                        </div>
                        @endif
                        <a href="{{ route('owner.payments') }}" class="card-action">
                            View Payments <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Customer Management -->
                <div class="dashboard-card customer-card">
                    <div class="card-header">
                        <i class="fas fa-users"></i>
                        <h3>Customers</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">{{ $recentBookings->pluck('user.id')->unique()->count() }}</div>
                        <div class="stat-label">Recent Customers</div>
                        <div class="stat-detail">
                            <small>From last 10 bookings</small>
                        </div>
                        <a href="{{ route('owner.users') }}" class="card-action">
                            View Customers <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Booking Status Overview -->
                <div class="dashboard-card span-half">
                    <div class="card-header">
                        <i class="fas fa-chart-pie"></i>
                        <h3>Booking Status</h3>
                    </div>
                    <div class="card-content">
                        <div class="status-grid">
                            <div class="status-item confirmed">
                                <div class="status-number">{{ $bookingStats['confirmed'] }}</div>
                                <div class="status-label">Confirmed</div>
                            </div>
                            <div class="status-item pending">
                                <div class="status-number">{{ $bookingStats['pending'] }}</div>
                                <div class="status-label">Pending</div>
                            </div>
                            <div class="status-item completed">
                                <div class="status-number">{{ $bookingStats['completed'] }}</div>
                                <div class="status-label">Completed</div>
                            </div>
                            <div class="status-item cancelled">
                                <div class="status-number">{{ $bookingStats['cancelled'] }}</div>
                                <div class="status-label">Cancelled</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue Chart -->
                <div class="dashboard-card span-half">
                    <div class="card-header">
                        <i class="fas fa-chart-line"></i>
                        <h3>Monthly Revenue</h3>
                    </div>
                    <div class="card-content">
                        <canvas id="revenueChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="dashboard-card span-full">
                    <div class="card-header">
                        <i class="fas fa-clock"></i>
                        <h3>Recent Bookings</h3>
                    </div>
                    <div class="card-content">
                        @if($recentBookings->count() > 0)
                            <div class="bookings-table">
                                <div class="table-header">
                                    <div class="table-cell">Guest</div>
                                    <div class="table-cell">Room</div>
                                    <div class="table-cell">Check-in</div>
                                    <div class="table-cell">Amount</div>
                                    <div class="table-cell">Status</div>
                                    <div class="table-cell">Date</div>
                                </div>
                                @foreach($recentBookings as $booking)
                                    <div class="table-row">
                                        <div class="table-cell">
                                            <div class="guest-info">
                                                <i class="fas fa-user"></i>
                                                <span>{{ $booking->user->name }}</span>
                                            </div>
                                        </div>
                                        <div class="table-cell">{{ $booking->room->name }}</div>
                                        <div class="table-cell">
                                            {{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}
                                        </div>
                                        <div class="table-cell">
                                            <strong>Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</strong>
                                        </div>
                                        <div class="table-cell">
                                            <span class="status-badge {{ $booking->status }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </div>
                                        <div class="table-cell">
                                            <small>{{ $booking->created_at->format('M d, H:i') }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="table-footer">
                                <a href="{{ route('owner.bookings') }}" class="view-all-link">
                                    View All Bookings <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-calendar-times"></i>
                                <h4>No Bookings Yet</h4>
                                <p>Once you receive bookings, they will appear here.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="dashboard-card span-full">
                    <div class="card-header">
                        <i class="fas fa-lightning-bolt"></i>
                        <h3>Quick Actions</h3>
                    </div>
                    <div class="card-content">
                        <div class="action-buttons">
                            <a href="{{ route('owner.bookings') }}" class="action-btn primary">
                                <i class="fas fa-calendar-check"></i>
                                View All Bookings
                            </a>
                            <a href="{{ route('owner.admin.create') }}" class="action-btn secondary">
                                <i class="fas fa-user-plus"></i>
                                Add New Admin
                            </a>
        </main>
    </div>

    <style>
        .payment-card .card-header {
            background: linear-gradient(135deg, #8B5CF6, #A855F7);
            color: white;
        }

        .customer-card .card-header {
            background: linear-gradient(135deg, #06B6D4, #0891B2);
            color: white;
        }

        .stat-trend.warning {
            color: #F59E0B;
        }

        .stat-trend.info {
            color: #3B82F6;
        }

        .status-item.completed {
            background: linear-gradient(135deg, #10B981, #059669);
            color: white;
        }

        .status-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-warning {
            background: #FEF3C7;
            color: #92400E;
            border: 1px solid #F59E0B;
        }
    </style>

    <!-- Revenue Chart Script -->
        </main>
    </div>

    <!-- Revenue Chart Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            const monthlyData = @json($monthlyData);
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthlyData.map(item => item.month),
                    datasets: [{
                        label: 'Revenue',
                        data: monthlyData.map(item => item.revenue),
                        borderColor: '#dc2626',
                        backgroundColor: 'rgba(220, 38, 38, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#dc2626',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        });
    </script>
</body>

</html>
