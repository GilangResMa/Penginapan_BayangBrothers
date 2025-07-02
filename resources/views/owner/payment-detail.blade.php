<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payment Details - Owner Panel</title>
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
                <a href="{{ route('owner.payments') }}" class="nav-item active">
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
                <div class="header-with-back">
                    <div>
                        <h1>Payment Details</h1>
                        <p>Complete information about payment transaction</p>
                    </div>
                    <a href="{{ route('owner.payments') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back to Payments
                    </a>
                </div>
            </header>

            <!-- Payment Information Cards -->
            <div class="cards-grid">
                <!-- Payment Status Card -->
                <div class="info-card">
                    <div class="card-header">
                        <h3><i class="fas fa-credit-card"></i> Payment Information</h3>
                    </div>
                    <div class="card-content">
                        <div class="info-row">
                            <label>Payment ID:</label>
                            <span class="payment-id">#{{ $payment->id }}</span>
                        </div>
                        <div class="info-row">
                            <label>Amount:</label>
                            <span class="amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="info-row">
                            <label>Payment Method:</label>
                            <span class="payment-method {{ $payment->payment_method }}">
                                @if($payment->payment_method == 'qris')
                                    <i class="fas fa-qrcode"></i> QRIS
                                @else
                                    <i class="fas fa-university"></i> Bank Transfer
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <label>Status:</label>
                            <span class="status-badge status-{{ $payment->status }}">
                                @switch($payment->status)
                                    @case('pending')
                                        <i class="fas fa-clock"></i> Pending Verification
                                        @break
                                    @case('verified')
                                        <i class="fas fa-check-circle"></i> Verified
                                        @break
                                    @case('rejected')
                                        <i class="fas fa-times-circle"></i> Rejected
                                        @break
                                @endswitch
                            </span>
                        </div>
                        <div class="info-row">
                            <label>Created:</label>
                            <span>{{ $payment->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        @if($payment->verified_at)
                        <div class="info-row">
                            <label>{{ $payment->status == 'verified' ? 'Verified' : 'Processed' }}:</label>
                            <span>{{ $payment->verified_at->format('d M Y, H:i') }}</span>
                        </div>
                        @endif
                        @if($payment->verifiedBy)
                        <div class="info-row">
                            <label>Verified by:</label>
                            <span>{{ $payment->verifiedBy->name }}</span>
                        </div>
                        @endif
                        @if($payment->verification_notes)
                        <div class="info-row">
                            <label>Notes:</label>
                            <span class="notes">{{ $payment->verification_notes }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Booking Information Card -->
                <div class="info-card">
                    <div class="card-header">
                        <h3><i class="fas fa-calendar-check"></i> Booking Information</h3>
                    </div>
                    <div class="card-content">
                        <div class="info-row">
                            <label>Booking Code:</label>
                            <span class="booking-code">{{ $payment->booking->booking_code }}</span>
                        </div>
                        <div class="info-row">
                            <label>Room:</label>
                            <span>{{ $payment->booking->room->name }}</span>
                        </div>
                        <div class="info-row">
                            <label>Check-in:</label>
                            <span>{{ $payment->booking->check_in->format('d M Y') }}</span>
                        </div>
                        <div class="info-row">
                            <label>Check-out:</label>
                            <span>{{ $payment->booking->check_out->format('d M Y') }}</span>
                        </div>
                        <div class="info-row">
                            <label>Duration:</label>
                            <span>{{ $payment->booking->check_in->diffInDays($payment->booking->check_out) }} nights</span>
                        </div>
                        <div class="info-row">
                            <label>Total Cost:</label>
                            <span class="amount">Rp {{ number_format($payment->booking->total_cost, 0, ',', '.') }}</span>
                        </div>
                        <div class="info-row">
                            <label>Booking Status:</label>
                            <span class="status-badge status-{{ $payment->booking->status }}">
                                {{ ucfirst($payment->booking->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Customer Information Card -->
                <div class="info-card">
                    <div class="card-header">
                        <h3><i class="fas fa-user"></i> Customer Information</h3>
                    </div>
                    <div class="card-content">
                        <div class="info-row">
                            <label>Name:</label>
                            <span>{{ $payment->booking->user->name }}</span>
                        </div>
                        <div class="info-row">
                            <label>Email:</label>
                            <span>{{ $payment->booking->user->email }}</span>
                        </div>
                        <div class="info-row">
                            <label>Phone:</label>
                            <span>{{ $payment->booking->user->phone ?? 'Not provided' }}</span>
                        </div>
                        <div class="info-row">
                            <label>Member since:</label>
                            <span>{{ $payment->booking->user->created_at->format('M Y') }}</span>
                        </div>
                        <div class="action-buttons mt-3">
                            <a href="{{ route('owner.users.show', $payment->booking->user->id) }}" 
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-user"></i>
                                View Customer Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Payment Proof Card -->
                @if($payment->proof_of_payment)
                <div class="info-card full-width">
                    <div class="card-header">
                        <h3><i class="fas fa-image"></i> Payment Proof</h3>
                    </div>
                    <div class="card-content">
                        <div class="payment-proof-container">
                            <img src="{{ asset('storage/' . $payment->proof_of_payment) }}" 
                                 alt="Payment Proof" 
                                 class="payment-proof-image"
                                 onclick="openModal(this.src)">
                            <p class="image-note">Click image to view full size</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="modal" onclick="closeModal()">
        <div class="modal-content">
            <span class="close">&times;</span>
            <img id="modalImage" src="" alt="Payment Proof">
        </div>
    </div>

    <style>
        .header-with-back {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .info-card.full-width {
            grid-column: 1 / -1;
        }

        .card-header {
            background: #F8F9FA;
            padding: 1rem;
            border-bottom: 1px solid #E5E7EB;
        }

        .card-header h3 {
            margin: 0;
            color: #374151;
            font-size: 1.1rem;
        }

        .card-content {
            padding: 1.5rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #F3F4F6;
        }

        .info-row:last-child {
            margin-bottom: 0;
            border-bottom: none;
        }

        .info-row label {
            font-weight: 600;
            color: #6B7280;
            min-width: 120px;
        }

        .info-row span {
            text-align: right;
            flex: 1;
        }

        .payment-id {
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }

        .booking-code {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            color: #374151;
        }

        .amount {
            font-weight: 600;
            color: #059669;
        }

        .payment-method.qris {
            color: #7C3AED;
            background-color: #F3F4F6;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .payment-method.bank_transfer {
            color: #059669;
            background-color: #F3F4F6;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .notes {
            background: #F9FAFB;
            padding: 0.5rem;
            border-radius: 4px;
            font-style: italic;
            color: #374151;
        }

        .payment-proof-container {
            text-align: center;
        }

        .payment-proof-image {
            max-width: 100%;
            max-height: 400px;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .payment-proof-image:hover {
            transform: scale(1.05);
        }

        .image-note {
            margin-top: 0.5rem;
            color: #6B7280;
            font-size: 0.9rem;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
        }

        .modal-content {
            position: relative;
            margin: auto;
            padding: 20px;
            width: 90%;
            max-width: 800px;
            top: 50%;
            transform: translateY(-50%);
        }

        .modal-content img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 25px;
            color: white;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>

    <script>
        function openModal(src) {
            document.getElementById('imageModal').style.display = 'block';
            document.getElementById('modalImage').src = src;
        }

        function closeModal() {
            document.getElementById('imageModal').style.display = 'none';
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>

</html>
