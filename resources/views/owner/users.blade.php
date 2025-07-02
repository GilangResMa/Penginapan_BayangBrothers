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

        <!-- Main Content -->
        <main class="main-content">
            <header class="content-header">
                <h1>Customer Management</h1>
                <p>View and manage all customers who have made bookings</p>
            </header>

            <!-- Filters -->
            <div class="filter-section">
                <form method="GET" action="{{ route('owner.users') }}" class="filter-form">
                    <div class="filter-group">
                        <div class="form-group">
                            <label for="search">Search</label>
                            <input type="text" id="search" name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Customer name or email...">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                                Search
                            </button>
                            <a href="{{ route('owner.users') }}" class="btn btn-secondary">
                                <i class="fas fa-refresh"></i>
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $users->total() }}</h3>
                        <p>Total Customers</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $users->where('bookings_count', '>', 1)->count() }}</h3>
                        <p>Repeat Customers</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ number_format($users->sum('bookings_count')) }}</h3>
                        <p>Total Bookings</p>
                    </div>
                </div>
            </div>

            <!-- Customers Table -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Total Bookings</th>
                            <th>Member Since</th>
                            <th>Recent Bookings</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="customer-profile">
                                    <div class="customer-avatar">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div class="customer-info">
                                        <strong>{{ $user->name }}</strong>
                                        @if($user->bookings_count > 1)
                                            <span class="badge badge-success">Repeat Customer</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="booking-count">{{ $user->bookings_count }}</span>
                            </td>
                            <td>
                                <div class="date-info">
                                    <strong>{{ $user->created_at->format('d M Y') }}</strong>
                                    <small>{{ $user->created_at->diffForHumans() }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="recent-bookings">
                                    @forelse($user->bookings->take(2) as $booking)
                                        <div class="booking-item">
                                            <span class="booking-code">{{ $booking->booking_code }}</span>
                                            <span class="booking-date">{{ $booking->created_at->format('M Y') }}</span>
                                            <span class="status-badge status-{{ $booking->status }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </div>
                                    @empty
                                        <span class="text-muted">No bookings</span>
                                    @endforelse
                                    @if($user->bookings->count() > 2)
                                        <small class="text-muted">+{{ $user->bookings->count() - 2 }} more</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('owner.users.show', $user->id) }}" 
                                       class="btn btn-sm btn-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-users"></i>
                                    <h3>No Customers Found</h3>
                                    <p>No customers match your current search criteria.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
            <div class="pagination-wrapper">
                {{ $users->appends(request()->query())->links() }}
            </div>
            @endif
        </main>
    </div>

    <style>
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

        .customer-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .customer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #3B82F6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .customer-info strong {
            display: block;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 500;
            margin-top: 2px;
        }

        .badge-success {
            background: #DEF7EC;
            color: #03543F;
        }

        .booking-count {
            font-weight: 600;
            font-size: 1.1rem;
            color: #3B82F6;
        }

        .date-info strong {
            display: block;
        }

        .date-info small {
            color: #6B7280;
        }

        .recent-bookings {
            max-width: 200px;
        }

        .booking-item {
            display: flex;
            flex-direction: column;
            margin-bottom: 0.5rem;
            padding: 0.25rem;
            background: #F9FAFB;
            border-radius: 4px;
            font-size: 0.8rem;
        }

        .booking-code {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            color: #374151;
        }

        .booking-date {
            color: #6B7280;
        }

        .text-muted {
            color: #9CA3AF;
            font-style: italic;
        }
    </style>
</body>

</html>
