<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard - Bayang Brothers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/admin.css'])
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-crown"></i>
                <span>Owner Panel</span>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <a href="{{ route('owner.dashboard') }}" class="nav-item active">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('owner.bookings') }}" class="nav-item">
                <i class="fas fa-calendar-check"></i>
                <span>Bookings</span>
            </a>
            <a href="{{ route('owner.revenue') }}" class="nav-item">
                <i class="fas fa-chart-line"></i>
                <span>Revenue</span>
            </a>
            <a href="{{ route('owner.admins') }}" class="nav-item">
                <i class="fas fa-users-cog"></i>
                <span>Manage Admins</span>
            </a>
            <a href="{{ route('homepage') }}" class="nav-item">
                <i class="fas fa-home"></i>
                <span>Back to Site</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="content-header">
            <h1>Owner Dashboard</h1>
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span>{{ $owner->name }}</span>
            </div>
        </header>

        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card revenue">
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Total Revenue</h3>
                        <p class="stat-number">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="stat-card bookings">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Total Bookings</h3>
                        <p class="stat-number">{{ $totalBookings }}</p>
                    </div>
                </div>

                <div class="stat-card monthly">
                    <div class="stat-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="stat-content">
                        <h3>This Month</h3>
                        <p class="stat-number">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="stat-card admins">
                    <div class="stat-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Admins</h3>
                        <p class="stat-number">{{ $totalAdmins }}</p>
                    </div>
                </div>
            </div>

            <!-- Charts and Analytics -->
            <div class="analytics-grid">
                <!-- Revenue Chart -->
                <div class="chart-card">
                    <div class="card-header">
                        <h3>Monthly Revenue (Last 12 Months)</h3>
                    </div>
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Booking Statistics -->
                <div class="chart-card">
                    <div class="card-header">
                        <h3>Booking Statistics</h3>
                    </div>
                    <div class="booking-stats">
                        <div class="stat-item">
                            <span class="stat-label">Confirmed</span>
                            <span class="stat-value confirmed">{{ $bookingStats['confirmed'] }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Pending</span>
                            <span class="stat-value pending">{{ $bookingStats['pending'] }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Cancelled</span>
                            <span class="stat-value cancelled">{{ $bookingStats['cancelled'] }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Success Rate</span>
                            <span class="stat-value success">{{ $bookingStats['success_rate'] }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="table-card">
                <div class="card-header">
                    <h3>Recent Bookings</h3>
                    <a href="{{ route('owner.bookings') }}" class="btn btn-primary">View All</a>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Booking Code</th>
                                <th>Guest</th>
                                <th>Room</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBookings as $booking)
                            <tr>
                                <td>{{ $booking->booking_code }}</td>
                                <td>{{ $booking->user->name }}</td>
                                <td>{{ $booking->room->name }}</td>
                                <td>{{ $booking->check_in->format('d M Y') }}</td>
                                <td>{{ $booking->check_out->format('d M Y') }}</td>
                                <td>Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</td>
                                <td>
                                    <span class="status status-{{ $booking->status }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No recent bookings</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Revenue Chart
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($monthlyData, 'month')) !!},
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: {!! json_encode(array_column($monthlyData, 'revenue')) !!},
                    borderColor: '#dc2626',
                    backgroundColor: 'rgba(220, 38, 38, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Revenue: Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
