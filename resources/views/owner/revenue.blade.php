<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenue Analytics - Bayang Brothers</title>
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
            <a href="{{ route('owner.dashboard') }}" class="nav-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('owner.bookings') }}" class="nav-item">
                <i class="fas fa-calendar-check"></i>
                <span>Bookings</span>
            </a>
            <a href="{{ route('owner.revenue') }}" class="nav-item active">
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
            <h1>Revenue Analytics</h1>
            <div class="header-actions">
                <button class="btn btn-secondary" onclick="exportRevenue()">
                    <i class="fas fa-download"></i>
                    Export Report
                </button>
            </div>
        </header>

        <!-- Filters -->
        <div class="filters-section">
            <form method="GET" action="{{ route('owner.revenue') }}" class="filters-form">
                <div class="filter-group">
                    <label for="year">Year:</label>
                    <select name="year" id="year">
                        @foreach($availableYears as $availableYear)
                            <option value="{{ $availableYear }}" {{ $year == $availableYear ? 'selected' : '' }}>
                                {{ $availableYear }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="month">Month:</label>
                    <select name="month" id="month">
                        <option value="">All Months</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i>
                    Apply Filter
                </button>
            </form>
        </div>

        <!-- Revenue Overview -->
        <div class="stats-grid">
            <div class="stat-card revenue">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $year }} Revenue</h3>
                    <p class="stat-number">Rp {{ number_format($currentYearRevenue, 0, ',', '.') }}</p>
                    <div class="stat-change {{ $revenueGrowth >= 0 ? 'positive' : 'negative' }}">
                        <i class="fas fa-{{ $revenueGrowth >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                        {{ number_format(abs($revenueGrowth), 1) }}% vs {{ $year - 1 }}
                    </div>
                </div>
            </div>

            <div class="stat-card comparison">
                <div class="stat-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $year - 1 }} Revenue</h3>
                    <p class="stat-number">Rp {{ number_format($previousYearRevenue, 0, ',', '.') }}</p>
                    <small>Previous year comparison</small>
                </div>
            </div>

            <div class="stat-card average">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-content">
                    <h3>Monthly Average</h3>
                    <p class="stat-number">Rp {{ number_format($currentYearRevenue / 12, 0, ',', '.') }}</p>
                    <small>Based on {{ $year }} data</small>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="analytics-grid">
            <!-- Monthly Revenue Chart -->
            <div class="chart-card full-width">
                <div class="card-header">
                    <h3>Monthly Revenue - {{ $year }}</h3>
                </div>
                <div class="chart-container">
                    <canvas id="monthlyRevenueChart"></canvas>
                </div>
            </div>

            <!-- Top Performing Rooms -->
            <div class="chart-card">
                <div class="card-header">
                    <h3>Top Performing Rooms</h3>
                </div>
                <div class="top-rooms-list">
                    @forelse($topRooms as $index => $room)
                    <div class="room-item">
                        <div class="room-rank">{{ $index + 1 }}</div>
                        <div class="room-info">
                            <strong>{{ $room->name }}</strong>
                            <span class="room-revenue">Rp {{ number_format($room->total_revenue ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="room-bar">
                            <div class="bar-fill" style="width: {{ $topRooms->isNotEmpty() && $topRooms->first()->total_revenue > 0 ? (($room->total_revenue ?? 0) / $topRooms->first()->total_revenue) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="fas fa-chart-bar"></i>
                        <p>No revenue data available</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Monthly Revenue Chart
        const ctx = document.getElementById('monthlyRevenueChart').getContext('2d');
        const monthlyRevenueChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_column($monthlyRevenue, 'month')) !!},
                datasets: [{
                    label: 'Monthly Revenue (Rp)',
                    data: {!! json_encode(array_column($monthlyRevenue, 'revenue')) !!},
                    backgroundColor: 'rgba(220, 38, 38, 0.8)',
                    borderColor: '#dc2626',
                    borderWidth: 1,
                    borderRadius: 4,
                    borderSkipped: false,
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
</body>
</html>
