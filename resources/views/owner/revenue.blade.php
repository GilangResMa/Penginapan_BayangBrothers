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

                <!-- Monthly Breakdown -->
                <div class="dashboard-card span-half">
                    <div class="card-header">
                        <i class="fas fa-table"></i>
                        <h3>Monthly Breakdown ({{ $year ?? date('Y') }})</h3>
                    </div>
                    <div class="card-content">
                        @if(isset($monthlyRevenue) && count($monthlyRevenue) > 0)
                            <div class="table-responsive">
                                <table class="admin-table compact-table">
                                    <thead>
                                        <tr>
                                            <th>Month</th>
                                            <th>Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($monthlyRevenue as $month)
                                        <tr>
                                            <td>
                                                <div class="month-name">{{ $month['month'] }} {{ $year ?? date('Y') }}</div>
                                            </td>
                                            <td>
                                                <div class="amount">Rp {{ number_format($month['revenue'], 0, ',', '.') }}</div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
        // Mobile menu toggle
        function toggleMobileMenu() {
            document.getElementById('sidebar').classList.toggle('active');
            document.querySelector('.mobile-overlay').classList.toggle('active');
            document.body.classList.toggle('menu-open');
        }
        
        document.getElementById('mobileMenuToggle').addEventListener('click', toggleMobileMenu);
        document.querySelector('.mobile-overlay').addEventListener('click', toggleMobileMenu);
    
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
