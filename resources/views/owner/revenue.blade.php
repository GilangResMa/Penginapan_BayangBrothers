<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Revenue Report - Owner Panel - Bayang Brothers</title>
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
                <a href="{{ route('owner.users') }}" class="nav-item">
                    <i class="fas fa-users"></i>
                    Customers
                </a>
                <a href="{{ route('owner.revenue') }}" class="nav-item active">
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
                <h1><i class="fas fa-chart-line"></i> Revenue Report</h1>
                <p>Comprehensive revenue analysis and financial insights for your business.</p>
            </header>

            <!-- Revenue Overview Cards -->
            <div class="dashboard-grid">
                <!-- Total Revenue -->
                <div class="dashboard-card revenue-card">
                    <div class="card-header">
                        <i class="fas fa-dollar-sign"></i>
                        <h3>Total Revenue</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">Rp {{ number_format($currentYearRevenue ?? 0, 0, ',', '.') }}</div>
                        <div class="stat-label">{{ $year ?? date('Y') }} earnings</div>
                        <div class="stat-trend {{ ($revenueGrowth ?? 0) > 0 ? 'positive' : 'neutral' }}">
                            <i class="fas fa-{{ ($revenueGrowth ?? 0) > 0 ? 'arrow-up' : 'minus' }}"></i>
                            {{ number_format($revenueGrowth ?? 0, 1) }}% from last year
                        </div>
                    </div>
                </div>

                <!-- Monthly Revenue -->
                <div class="dashboard-card booking-card">
                    <div class="card-header">
                        <i class="fas fa-calendar-alt"></i>
                        <h3>This Month</h3>
                    </div>
                    <div class="card-content">
                        @php
                            $currentMonthRevenue = collect($monthlyRevenue ?? [])->where('month', date('M'))->first()['revenue'] ?? 0;
                        @endphp
                        <div class="stat-number">Rp {{ number_format($currentMonthRevenue, 0, ',', '.') }}</div>
                        <div class="stat-label">{{ date('F Y') }} revenue</div>
                        <div class="stat-trend neutral">
                            <i class="fas fa-calendar"></i>
                            Current month
                        </div>
                    </div>
                </div>

                <!-- Top Room Revenue -->
                <div class="dashboard-card room-card">
                    <div class="card-header">
                        <i class="fas fa-star"></i>
                        <h3>Top Room</h3>
                    </div>
                    <div class="card-content">
                        @php
                            $topRoom = $topRooms->first() ?? null;
                        @endphp
                        @if($topRoom)
                            <div class="stat-number">Rp {{ number_format($topRoom->total_revenue ?? 0, 0, ',', '.') }}</div>
                            <div class="stat-label">{{ $topRoom->name }}</div>
                            <div class="stat-detail">
                                Best performing room
                            </div>
                        @else
                            <div class="stat-number">Rp 0</div>
                            <div class="stat-label">No data</div>
                            <div class="stat-detail">
                                No bookings yet
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Growth Rate -->
                <div class="dashboard-card admin-card">
                    <div class="card-header">
                        <i class="fas fa-chart-bar"></i>
                        <h3>Growth Rate</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">{{ number_format($revenueGrowth ?? 0, 1) }}%</div>
                        <div class="stat-label">Year over year</div>
                        <div class="stat-trend {{ ($revenueGrowth ?? 0) > 0 ? 'positive' : 'neutral' }}">
                            <i class="fas fa-{{ ($revenueGrowth ?? 0) > 0 ? 'trending-up' : 'minus' }}"></i>
                            Compared to {{ ($year ?? date('Y')) - 1 }}
                        </div>
                    </div>
                </div>

                <!-- Revenue Chart -->
                <div class="dashboard-card span-full">
                    <div class="card-header">
                        <i class="fas fa-chart-area"></i>
                        <h3>Revenue Trend ({{ $year ?? date('Y') }})</h3>
                    </div>
                    <div class="card-content">
                        <div class="chart-container">
                            <canvas id="revenueChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Revenue by Room Type -->
                <div class="dashboard-card span-half">
                    <div class="card-header">
                        <i class="fas fa-bed"></i>
                        <h3>Top Performing Rooms</h3>
                    </div>
                    <div class="card-content">
                        @if(isset($topRooms) && $topRooms->count() > 0)
                            <div class="room-revenue-list">
                                @foreach($topRooms->take(5) as $room)
                                <div class="room-revenue-item">
                                    <div class="room-info">
                                        <span class="room-name">{{ $room->name }}</span>
                                        <span class="room-bookings">{{ $room->bookings_count ?? 0 }} bookings</span>
                                    </div>
                                    <div class="room-amount">
                                        Rp {{ number_format($room->total_revenue ?? 0, 0, ',', '.') }}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-chart-pie"></i>
                                <h4>No Room Revenue Data</h4>
                                <p>Revenue data will appear here once you have completed bookings.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Monthly Breakdown -->
                <div class="dashboard-card span-half">
                    <div class="card-header">
                        <i class="fas fa-table"></i>
                        <h3>Monthly Breakdown ({{ $year ?? date('Y') }})</h3>
                    </div>
                    <div class="card-content">
                        @if(isset($monthlyRevenue) && count($monthlyRevenue) > 0)
                            <div class="monthly-breakdown">
                                @foreach($monthlyRevenue as $month)
                                <div class="month-item">
                                    <div class="month-info">
                                        <span class="month-name">{{ $month['month'] }} {{ $year ?? date('Y') }}</span>
                                        <span class="month-bookings">Monthly revenue</span>
                                    </div>
                                    <div class="month-revenue">
                                        Rp {{ number_format($month['revenue'], 0, ',', '.') }}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-calendar-times"></i>
                                <h4>No Monthly Data</h4>
                                <p>Monthly breakdown will appear here once you have revenue data.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Revenue Actions -->
                <div class="dashboard-card span-full">
                    <div class="card-header">
                        <i class="fas fa-cogs"></i>
                        <h3>Revenue Management</h3>
                    </div>
                    <div class="card-content">
                        <div class="action-buttons">
                            <a href="{{ route('owner.bookings') }}" class="action-btn primary">
                                <i class="fas fa-calendar-check"></i>
                                View All Bookings
                            </a>
                            <a href="{{ route('owner.dashboard') }}" class="action-btn secondary">
                                <i class="fas fa-tachometer-alt"></i>
                                Back to Dashboard
                            </a>
                            <button onclick="window.print()" class="action-btn outline">
                                <i class="fas fa-print"></i>
                                Print Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Revenue Chart Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            const monthlyData = @json($monthlyRevenue ?? []);
            
            if (monthlyData.length > 0) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: monthlyData.map(item => item.month),
                        datasets: [{
                            label: 'Revenue (Rp)',
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
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        }
                    }
                });
            } else {
                // Show empty chart message
                const canvas = document.getElementById('revenueChart');
                const ctx = canvas.getContext('2d');
                ctx.fillStyle = '#6b7280';
                ctx.font = '16px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('No revenue data available', canvas.width / 2, canvas.height / 2);
            }
        });
    </script>
</body>

</html>
