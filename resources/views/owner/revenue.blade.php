<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Revenue Analytics - Bayang Brothers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/admin.css'])
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
                    Manage Bookings
                </a>
                <a href="{{ route('owner.revenue') }}" class="nav-item active">
                    <i class="fas fa-chart-line"></i>
                    Revenue Analytics
                </a>
                <a href="{{ route('owner.admins') }}" class="nav-item">
                    <i class="fas fa-users-cog"></i>
                    Manage Admins
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
                <h1>Revenue Analytics</h1>
                <p>Track your business performance and revenue trends</p>
            </header>
            <!-- Revenue Overview Cards -->
            <div class="dashboard-grid">
                <!-- Current Year Revenue -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-dollar-sign"></i>
                        <h3>{{ $year }} Revenue</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">Rp {{ number_format($currentYearRevenue, 0, ',', '.') }}</div>
                        <div class="stat-label">Current Year Total</div>
                        <div class="stat-change {{ $revenueGrowth >= 0 ? 'positive' : 'negative' }}">
                            <i class="fas fa-{{ $revenueGrowth >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                            {{ number_format(abs($revenueGrowth), 1) }}% vs {{ $year - 1 }}
                        </div>
                    </div>
                </div>

                <!-- Previous Year Revenue -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-chart-bar"></i>
                        <h3>{{ $year - 1 }} Revenue</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">Rp {{ number_format($previousYearRevenue, 0, ',', '.') }}</div>
                        <div class="stat-label">Previous Year Total</div>
                        <small>For comparison</small>
                    </div>
                </div>

                <!-- Monthly Average -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-calendar-alt"></i>
                        <h3>Monthly Average</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">Rp {{ number_format($currentYearRevenue / 12, 0, ',', '.') }}</div>
                        <div class="stat-label">Average per Month</div>
                        <small>Based on {{ $year }} data</small>
                    </div>
                </div>

                <!-- Filter Card -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-filter"></i>
                        <h3>Filter Data</h3>
                    </div>
                    <div class="card-content">
                        <form method="GET" action="{{ route('owner.revenue') }}" class="filter-form">
                            <div class="form-group">
                                <label for="year">Year:</label>
                                <select name="year" id="year" class="form-control">
                                    @foreach($availableYears as $availableYear)
                                        <option value="{{ $availableYear }}" {{ $year == $availableYear ? 'selected' : '' }}>
                                            {{ $availableYear }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="month">Month:</label>
                                <select name="month" id="month" class="form-control">
                                    <option value="">All Months</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="action-btn primary">
                                    <i class="fas fa-filter"></i>
                                    Apply Filter
                                </button>
                                <button type="button" class="action-btn secondary" onclick="exportRevenue()">
                                    <i class="fas fa-download"></i>
                                    Export
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Monthly Revenue Chart -->
                <div class="dashboard-card span-full">
                    <div class="card-header">
                        <i class="fas fa-chart-line"></i>
                        <h3>Monthly Revenue Trend - {{ $year }}</h3>
                    </div>
                    <div class="card-content">
                        <div class="chart-container">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
                
            </div>
        </main>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Revenue Chart
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($monthlyRevenue, 'month')) !!},
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: {!! json_encode(array_column($monthlyRevenue, 'revenue')) !!},
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

        function exportRevenue() {
            const year = document.getElementById('year').value;
            const month = document.getElementById('month').value;
            
            let exportUrl = '{{ route("owner.export.revenue") }}?year=' + year;
            if (month) {
                exportUrl += '&month=' + month;
            }
            
            window.location.href = exportUrl;
        }
    </script>

    <style>
        /* Owner specific branding */
        .sidebar-header .logo-icon {
            color: #fbbf24;
        }

        .chart-container {
            height: 400px;
            position: relative;
        }

        .filter-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label {
            font-weight: 500;
            color: #374151;
        }

        .form-control {
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        .form-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .stat-change {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            margin-top: 0.5rem;
        }

        .stat-change.positive {
            color: #059669;
        }

        .stat-change.negative {
            color: #dc2626;
        }

        .top-rooms-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .room-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            background: #f9fafb;
            border-radius: 0.5rem;
        }

        .room-rank {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            background: #dc2626;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .room-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .room-revenue {
            color: #059669;
            font-weight: 500;
        }

        .room-bar {
            width: 100px;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }

        .bar-fill {
            height: 100%;
            background: #dc2626;
            transition: width 0.3s ease;
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #6b7280;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #d1d5db;
        }
    </style>
</body>

</html>
    </script>
</body>
</html>
