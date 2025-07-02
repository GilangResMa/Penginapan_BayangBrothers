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
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-content">
                    <div class="booking-details-grid">
                        <div class="detail-item">
                            <label>Booking Code</label>
                            <div class="detail-value">{{ $booking->booking_code }}</div>
                        </div>
                        <div class="detail-item">
                            <label>Booking Date</label>
                            <div class="detail-value">{{ $booking->created_at ? $booking->created_at->format('d M Y, H:i') : 'N/A' }}</div>
                        </div>
                        <div class="detail-item">
                            <label>Check-in Date</label>
                            <div class="detail-value">{{ $booking->check_in ? $booking->check_in->format('d M Y') : 'N/A' }}</div>
                        </div>
                        <div class="detail-item">
                            <label>Check-out Date</label>
                            <div class="detail-value">{{ $booking->check_out ? $booking->check_out->format('d M Y') : 'N/A' }}</div>
                        </div>
                        <div class="detail-item">
                            <label>Number of Nights</label>
                            <div class="detail-value">{{ $booking->check_in && $booking->check_out ? $booking->check_in->diffInDays($booking->check_out) : 0 }} nights</div>
                        </div>
                        <div class="detail-item">
                            <label>Number of Guests</label>
                            <div class="detail-value">{{ $booking->guests }} guests</div>
                        </div>
                        <div class="detail-item">
                            <label>Extra Bed</label>
                            <div class="detail-value">
                                @if($booking->extra_bed)
                                    <span class="extra-bed-tag"><i class="fas fa-check"></i> Yes</span>
                                @else
                                    <span class="text-muted">No</span>
                                @endif
                            </div>
                        </div>
                        <div class="detail-item">
                            <label>Total Cost</label>
                            <div class="detail-value amount-highlight">Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</div>
                        </div>
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
                    <div class="guest-details-grid">
                        <div class="detail-item">
                            <label>Guest Name</label>
                            <div class="detail-value">{{ $booking->user->name }}</div>
                        </div>
                        <div class="detail-item">
                            <label>Email</label>
                            <div class="detail-value">{{ $booking->user->email }}</div>
                        </div>
                        <div class="detail-item">
                            <label>Phone</label>
                            <div class="detail-value">{{ $booking->user->phone ?? 'Not provided' }}</div>
                        </div>
                        <div class="detail-item">
                            <label>Member Since</label>
                            <div class="detail-value">{{ $booking->user->created_at ? $booking->user->created_at->format('d M Y') : 'N/A' }}</div>
                        </div>
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
                    <div class="room-details-grid">
                        <div class="detail-item">
                            <label>Room Name</label>
                            <div class="detail-value">{{ $booking->room->name }}</div>
                        </div>
                        <div class="detail-item">
                            <label>Room Type</label>
                            <div class="detail-value">{{ $booking->room->type ?? 'Standard' }}</div>
                        </div>
                        <div class="detail-item">
                            <label>Capacity</label>
                            <div class="detail-value">{{ $booking->room->capacity ?? 'N/A' }} persons</div>
                        </div>
                        <div class="detail-item">
                            <label>Price per Night</label>
                            <div class="detail-value">Rp {{ number_format($booking->room->price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            @if($booking->payment)
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-credit-card"></i>
                    <h3>Payment Information</h3>
                    <div class="card-actions">
                        @if($booking->payment->status == 'verified')
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
                    <div class="payment-details-grid">
                        <div class="detail-item">
                            <label>Payment Method</label>
                            <div class="detail-value">
                                @if($booking->payment->payment_method == 'qris')
                                    <i class="fas fa-qrcode"></i> QRIS
                                @else
                                    <i class="fas fa-university"></i> Bank Transfer
                                @endif
                            </div>
                        </div>
                        <div class="detail-item">
                            <label>Payment Date</label>
                            <div class="detail-value">{{ $booking->payment->created_at ? $booking->payment->created_at->format('d M Y, H:i') : 'N/A' }}</div>
                        </div>
                        @if($booking->payment->verified_at)
                        <div class="detail-item">
                            <label>Verified Date</label>
                            <div class="detail-value">{{ $booking->payment->verified_at->format('d M Y, H:i') }}</div>
                        </div>
                        @endif
                        @if($booking->payment->verified_by)
                        <div class="detail-item">
                            <label>Verified By</label>
                            <div class="detail-value">{{ $booking->payment->verifiedBy->name ?? 'System' }}</div>
                        </div>
                        @endif
                    </div>

                    @if($booking->payment->payment_proof)
                    <div class="payment-proof">
                        <label>Payment Proof</label>
                        <div class="proof-image">
                            <img src="{{ asset('storage/' . $booking->payment->payment_proof) }}" alt="Payment Proof" class="payment-proof-img">
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-exclamation-circle"></i>
                    <h3>Payment Information</h3>
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
                        @if($booking->payment)
                        <a href="{{ route('owner.payments.show', $booking->payment->id) }}" class="action-btn primary">
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

    @if(session('success'))
        <div class="alert alert-success" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <style>
    .booking-details-grid,
    .guest-details-grid,
    .room-details-grid,
    .payment-details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .detail-item {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid var(--primary-color);
    }

    .detail-item label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }

    .detail-value {
        font-size: 1rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .amount-highlight {
        font-size: 1.25rem;
        color: var(--success-color);
    }

    .extra-bed-tag {
        background: var(--success-color);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.875rem;
    }

    .payment-proof {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e9ecef;
    }

    .payment-proof label {
        display: block;
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #2c3e50;
    }

    .payment-proof-img {
        max-width: 300px;
        height: auto;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .payment-proof-img:hover {
        transform: scale(1.05);
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-confirmed {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-completed {
        background: #d4edda;
        color: #155724;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .status-verified {
        background: #d4edda;
        color: #155724;
    }

    .status-rejected {
        background: #f8d7da;
        color: #721c24;
    }

    @media (max-width: 768px) {
        .booking-details-grid,
        .guest-details-grid,
        .room-details-grid,
        .payment-details-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>
</body>

</html>
