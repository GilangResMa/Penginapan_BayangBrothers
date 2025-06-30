<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bookings Management - Bayang Brothers</title>
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
                <a href="{{ route('owner.bookings') }}" class="nav-item active">
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
                <h1>Bookings Management</h1>
                <p>Manage all bookings and reservations</p>
            </header>
            <!-- Filters -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-filter"></i>
                    <h3>Filter Bookings</h3>
                </div>
                <div class="card-content">
                    <form method="GET" action="{{ route('owner.bookings') }}" class="filters-form">
                        <div class="filter-grid">
                            <div class="filter-group">
                                <label for="status">Status:</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>

                            <div class="filter-group">
                                <label for="date_from">From:</label>
                                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="form-control">
                            </div>

                            <div class="filter-group">
                                <label for="date_to">To:</label>
                                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="form-control">
                            </div>

                            <div class="filter-group">
                                <label for="search">Search:</label>
                                <input type="text" name="search" id="search" placeholder="Booking code or guest name" value="{{ request('search') }}" class="form-control">
                            </div>
                        </div>

                        <div class="filter-actions">
                            <button type="submit" class="action-btn primary">
                                <i class="fas fa-search"></i>
                                Filter
                            </button>

                            <a href="{{ route('owner.bookings') }}" class="action-btn secondary">
                                <i class="fas fa-times"></i>
                                Clear
                            </a>

                            <button type="button" class="action-btn tertiary" onclick="exportBookings()">
                                <i class="fas fa-download"></i>
                                Export CSV
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bookings Table -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-calendar-check"></i>
                    <h3>All Bookings</h3>
                </div>
                <div class="card-content">
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Booking Code</th>
                                    <th>Guest Info</th>
                                    <th>Room</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Guests</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                <tr>
                                    <td>
                                        <strong>{{ $booking->booking_code }}</strong>
                                    </td>
                                    <td>
                                        <div class="guest-info">
                                            <strong>{{ $booking->user->name }}</strong><br>
                                            <small>{{ $booking->user->email }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $booking->room->name }}</td>
                                    <td>{{ $booking->check_in->format('d M Y') }}</td>
                                    <td>{{ $booking->check_out->format('d M Y') }}</td>
                                    <td>
                                        {{ $booking->guests }} guest{{ $booking->guests > 1 ? 's' : '' }}
                                        @if($booking->extra_bed)
                                            <br><small class="extra-bed">+ Extra Bed</small>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</strong>
                                    </td>
                                    <td>
                                        <span class="status status-{{ $booking->status }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('owner.booking.show', $booking->id) }}" class="action-btn primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                            View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <div class="empty-state">
                                            <i class="fas fa-calendar-times"></i>
                                            <p>No bookings found</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($bookings->hasPages())
                    <div class="pagination-wrapper">
                        {{ $bookings->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <style>
        /* Owner specific branding */
        .sidebar-header .logo-icon {
            color: #fbbf24;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-group label {
            font-weight: 500;
            color: #374151;
        }

        .form-control {
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        .filter-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .guest-info strong {
            color: #1f2937;
        }

        .guest-info small {
            color: #6b7280;
        }

        .extra-bed {
            color: #059669;
            font-weight: 500;
        }

        .status {
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-confirmed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .empty-state {
            padding: 2rem;
            text-align: center;
            color: #6b7280;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #d1d5db;
        }

        .pagination-wrapper {
            padding: 1rem;
            border-top: 1px solid #e5e7eb;
        }
    </style>

    <script>
        function exportBookings() {
            const urlParams = new URLSearchParams(window.location.search);
            const exportUrl = '{{ route("owner.export.revenue") }}?' + urlParams.toString();
            window.location.href = exportUrl;
        }
    </script>
</body>

</html>
</body>
</html>
