<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings Management - Bayang Brothers</title>
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
            <a href="{{ route('owner.bookings') }}" class="nav-item active">
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
            <h1>Bookings Management</h1>
            <div class="header-actions">
                <button class="btn btn-secondary" onclick="exportBookings()">
                    <i class="fas fa-download"></i>
                    Export CSV
                </button>
            </div>
        </header>

        <!-- Filters -->
        <div class="filters-section">
            <form method="GET" action="{{ route('owner.bookings') }}" class="filters-form">
                <div class="filter-group">
                    <label for="status">Status:</label>
                    <select name="status" id="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="date_from">From:</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}">
                </div>

                <div class="filter-group">
                    <label for="date_to">To:</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}">
                </div>

                <div class="filter-group">
                    <label for="search">Search:</label>
                    <input type="text" name="search" id="search" placeholder="Booking code or guest name" value="{{ request('search') }}">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                    Filter
                </button>

                <a href="{{ route('owner.bookings') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Clear
                </a>
            </form>
        </div>

        <!-- Bookings Table -->
        <div class="table-card">
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
                            <th>Booking Date</th>
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
                            <td>{{ $booking->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('owner.booking.show', $booking->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                    View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center">
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

        <!-- Summary Stats -->
        <div class="summary-stats">
            <div class="stat-item">
                <span class="label">Total Bookings:</span>
                <span class="value">{{ $bookings->total() }}</span>
            </div>
            <div class="stat-item">
                <span class="label">Showing:</span>
                <span class="value">{{ $bookings->firstItem() ?? 0 }} - {{ $bookings->lastItem() ?? 0 }}</span>
            </div>
        </div>
    </main>

    <script>
        function exportBookings() {
            const urlParams = new URLSearchParams(window.location.search);
            const exportUrl = '{{ route("owner.export.revenue") }}?' + urlParams.toString();
            window.location.href = exportUrl;
        }
    </script>
</body>
</html>
