<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Owner Dashboard - Bayang Brothers</title>
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
                <a href="{{ route('owner.dashboard') }}" class="nav-item active">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
                <a href="{{ route('owner.bookings') }}" class="nav-item">
                    <i class="fas fa-calendar-check"></i>
                    Manage Bookings
                </a>
                <a href="{{ route('owner.revenue') }}" class="nav-item">
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
                <h1>Owner Dashboard</h1>
                <p>Welcome to Bayang Brothers Owner Panel, {{ $owner->name }}</p>
            </header>

            <div class="dashboard-grid">
                <!-- Revenue Stats -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-dollar-sign"></i>
                        <h3>Total Revenue</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                        <div class="stat-label">All Time Revenue</div>
                        <a href="{{ route('owner.revenue') }}" class="card-action">
                            View Analytics <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Monthly Revenue -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-chart-bar"></i>
                        <h3>Monthly Revenue</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</div>
                        <div class="stat-label">This Month</div>
                        <a href="{{ route('owner.revenue') }}" class="card-action">
                            View Details <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Bookings Stats -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-calendar-check"></i>
                        <h3>Bookings</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">{{ $totalBookings }}</div>
                        <div class="stat-label">Total Bookings</div>
                        <a href="{{ route('owner.bookings') }}" class="card-action">
                            Manage Bookings <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Rooms Stats -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-bed"></i>
                        <h3>Rooms</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">{{ $totalRooms }}</div>
                        <div class="stat-label">Total Rooms</div>
                        <a href="{{ route('owner.revenue') }}" class="card-action">
                            View Performance <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Admin Management -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-users-cog"></i>
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

                <!-- Booking Performance -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-chart-pie"></i>
                        <h3>Success Rate</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">{{ $bookingStats['success_rate'] }}%</div>
                        <div class="stat-label">Booking Success Rate</div>
                        <a href="{{ route('owner.bookings') }}" class="card-action">
                            View Details <i class="fas fa-arrow-right"></i>
                        </a>
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
                            <a href="{{ route('owner.admins') }}" class="action-btn primary">
                                <i class="fas fa-user-plus"></i>
                                Add New Admin
                            </a>
                            <a href="{{ route('owner.bookings') }}" class="action-btn secondary">
                                <i class="fas fa-calendar-check"></i>
                                Manage Bookings
                            </a>
                            <a href="{{ route('owner.revenue') }}" class="action-btn tertiary">
                                <i class="fas fa-chart-line"></i>
                                View Analytics
                            </a>
                            <a href="{{ route('homepage') }}" class="action-btn tertiary">
                                <i class="fas fa-eye"></i>
                                View Website
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <style>
        /* Owner specific branding */
        .sidebar-header .logo-icon {
            color: #fbbf24;
        }
    </style>
</body>

</html>
