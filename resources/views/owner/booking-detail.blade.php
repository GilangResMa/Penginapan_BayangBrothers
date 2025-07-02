<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Booking Details - Owner Panel - Bayang Brothers</title>
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
                <a href="{{ route('owner.bookings') }}" class="nav-item active">
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
                <h1><i class="fas fa-calendar-check"></i> Booking Details</h1>
                <p>Detailed information about booking #{{ $booking->booking_code }}</p>
            </header>

            <!-- Booking Information -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i>
                    <h3>Booking Information</h3>
                    <div class="card-actions">
                        <span class="status-badge status-{{ $booking->status }}">
                            <i class="fas fa-{{ 
                                $booking->status == 'pending' ? 'clock' : 
                                ($booking->status == 'confirmed' ? 'check-circle' : 
                                ($booking->status == 'completed' ? 'star' : 'times-circle')) 
                            }}"></i>
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-content">
                    <div class="table-responsive">
                        <table class="admin-table details-table">
                            <tbody>
                                <tr>
                                    <th><i class="fas fa-bookmark fa-fw text-primary"></i> Booking Code</th>
                                    <td>{{ $booking->booking_code }}</td>
                                    <th><i class="fas fa-calendar-alt fa-fw text-primary"></i> Booking Date</th>
                                    <td>{{ $booking->created_at ? $booking->created_at->format('d M Y, H:i') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-sign-in-alt fa-fw text-primary"></i> Check-in Date</th>
                                    <td>{{ $booking->check_in ? $booking->check_in->format('d M Y') : 'N/A' }}</td>
                                    <th><i class="fas fa-sign-out-alt fa-fw text-primary"></i> Check-out Date</th>
                                    <td>{{ $booking->check_out ? $booking->check_out->format('d M Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-moon fa-fw text-primary"></i> Number of Nights</th>
                                    <td>{{ $booking->check_in && $booking->check_out ? $booking->check_in->diffInDays($booking->check_out) : 0 }} nights</td>
                                    <th><i class="fas fa-users fa-fw text-primary"></i> Number of Guests</th>
                                    <td>{{ $booking->guests }} guests</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-bed fa-fw text-primary"></i> Extra Bed</th>
                                    <td>
                                        @if ($booking->extra_bed)
                                            <span class="extra-bed-tag"><i class="fas fa-check"></i> Yes</span>
                                        @else
                                            <span class="text-muted">No</span>
                                        @endif
                                    </td>
                                    <th><i class="fas fa-money-bill-wave fa-fw text-primary"></i> Total Cost</th>
                                    <td class="amount-highlight">Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Guest Information -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-user"></i>
                    <h3>Guest Information</h3>
                </div>
                <div class="card-content">
                    <div class="table-responsive">
                        <table class="admin-table details-table">
                            <tbody>
                                <tr>
                                    <th><i class="fas fa-user fa-fw text-primary"></i> Guest Name</th>
                                    <td>{{ $booking->user->name }}</td>
                                    <th><i class="fas fa-envelope fa-fw text-primary"></i> Email</th>
                                    <td>{{ $booking->user->email }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-phone fa-fw text-primary"></i> Phone</th>
                                    <td>{{ $booking->user->phone ?? 'Not provided' }}</td>
                                    <th><i class="fas fa-user-clock fa-fw text-primary"></i> Member Since</th>
                                    <td>{{ $booking->user->created_at ? $booking->user->created_at->format('d M Y') : 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Room Information -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-bed"></i>
                    <h3>Room Information</h3>
                </div>
                <div class="card-content">
                    <div class="table-responsive">
                        <table class="admin-table details-table">
                            <tbody>
                                <tr>
                                    <th><i class="fas fa-door-open fa-fw text-primary"></i> Room Name</th>
                                    <td>{{ $booking->room->name }}</td>
                                    <th><i class="fas fa-tag fa-fw text-primary"></i> Room Type</th>
                                    <td>{{ $booking->room->type ?? 'Standard' }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-users fa-fw text-primary"></i> Capacity</th>
                                    <td>{{ $booking->room->capacity ?? 'N/A' }} persons</td>
                                    <th><i class="fas fa-tag fa-fw text-primary"></i> Price per Night</th>
                                    <td class="amount-highlight">Rp {{ number_format($booking->room->price, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            @if ($booking->payment)
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-credit-card"></i>
                        <h3>Payment Information</h3>
                        <div class="card-actions">
                            @if ($booking->payment->status == 'verified')
                                <span class="status-badge status-verified">
                                    <i class="fas fa-check-circle"></i> Verified
                                </span>
                            @elseif($booking->payment->status == 'pending')
                                <span class="status-badge status-pending">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                            @else
                                <span class="status-badge status-rejected">
                                    <i class="fas fa-times-circle"></i> Rejected
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="table-responsive">
                            <table class="admin-table details-table">
                                <tbody>
                                    <tr>
                                        <th><i class="fas fa-credit-card fa-fw text-primary"></i> Payment Method</th>
                                        <td>
                                            @if ($booking->payment->payment_method == 'qris')
                                                <i class="fas fa-qrcode"></i> QRIS
                                            @else
                                                <i class="fas fa-university"></i> Bank Transfer
                                            @endif
                                        </td>
                                        <th><i class="fas fa-calendar-check fa-fw text-primary"></i> Payment Date</th>
                                        <td>{{ $booking->payment->created_at ? $booking->payment->created_at->format('d M Y, H:i') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fas fa-info-circle fa-fw text-primary"></i> Status</th>
                                        <td>
                                            <span class="status-badge status-{{ $booking->payment->status }}">
                                                <i class="fas fa-{{ 
                                                    $booking->payment->status == 'pending' ? 'clock' : 
                                                    ($booking->payment->status == 'verified' ? 'check-circle' : 'times-circle')
                                                }}"></i>
                                                {{ ucfirst($booking->payment->status) }}
                                            </span>
                                        </td>
                                        @if ($booking->payment->verified_at)
                                        <th><i class="fas fa-check-circle fa-fw text-primary"></i> Verified Date</th>
                                        <td>{{ $booking->payment->verified_at->format('d M Y, H:i') }}</td>
                                        @elseif ($booking->payment->verified_by)
                                        <th><i class="fas fa-user-check fa-fw text-primary"></i> Verified By</th>
                                        <td>{{ $booking->payment->verifiedBy->name ?? 'System' }}</td>
                                        @else
                                        <th><i class="fas fa-money-bill-wave fa-fw text-primary"></i> Amount</th>
                                        <td class="amount-highlight">Rp {{ number_format($booking->payment->amount ?? $booking->total_cost, 0, ',', '.') }}</td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        @if ($booking->payment->payment_proof)
                            <div class="payment-proof mt-4">
                                <h4 class="mb-2">Payment Proof</h4>
                                <div class="proof-image">
                                    <img src="{{ asset('storage/' . $booking->payment->payment_proof) }}"
                                        alt="Payment Proof" class="payment-proof-img">
                                </div>
                            </div>
                        @endif
                    <div class="card-content">
                        <div class="table-responsive">
                            <table class="admin-table details-table">
                                <tbody>
                                    <tr>
                                        <th><i class="fas fa-exclamation-circle fa-fw text-primary"></i> Payment Status</th>
                                        <td colspan="3">
                                            <div class="empty-state-inline">
                                                <i class="fas fa-credit-card text-danger"></i>
                                                <span>No payment has been made for this booking</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><i class="fas fa-money-bill-wave fa-fw text-primary"></i> Expected Amount</th>
                                        <td class="amount-highlight">Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</td>
                                        <th><i class="fas fa-calendar-alt fa-fw text-primary"></i> Due Date</th>
                                        <td>{{ $booking->check_in ? $booking->check_in->subDays(1)->format('d M Y') : 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>3>Payment Information</h3>
                    </div>
                    <div class="card-content">
                        <div class="empty-state">
                            <i class="fas fa-credit-card"></i>
                            <h4>No Payment Found</h4>
                            <p>This booking has not been paid yet.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-lightning-bolt"></i>
                    <h3>Actions</h3>
                </div>
                <div class="card-content">
                    <div class="action-buttons">
                        <a href="{{ route('owner.bookings') }}" class="action-btn secondary">
                            <i class="fas fa-arrow-left"></i>
                            Back to Bookings
                        </a>
                        @if ($booking->payment)
                            <a href="{{ route('owner.payments.show', $booking->payment->id) }}"
                                class="action-btn primary">
                                <i class="fas fa-credit-card"></i>
                                View Payment Details
                            </a>
                        @endif
                        <a href="{{ route('owner.users.show', $booking->user->id) }}" class="action-btn outline">
                            <i class="fas fa-user"></i>
                            View Guest Profile
                        </a>
                        <button onclick="window.print()" class="action-btn outline">
                            <i class="fas fa-print"></i>
                            Print Details
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @if (session('success'))
        <div class="alert alert-success" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
    @endif

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
</div>
@if (session('error'))
    <div class="alert alert-error" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
    </div>
@endif

</body>

</html>
