<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Bayang Brothers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/payment.css'])
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <!-- Logo -->
            <div class="logo-section">
                <i class="fas fa-home logo-icon"></i>
                <div>
                    <div class="logo-text">Bayang Brothers</div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="navigation">
                <a href="{{ route('homepage') }}" class="nav-link">
                    <i class="fas fa-home"></i>
                    <span>Home</span>
                </a>
                <a href="{{ route('room.index') }}" class="nav-link">
                    <i class="fas fa-bed"></i>
                    <span>Room</span>
                </a>
                <a href="{{ route('about') }}" class="nav-link">
                    <i class="fas fa-info-circle"></i>
                    <span>About</span>
                </a>
                <a href="{{ route('faq') }}" class="nav-link">
                    <i class="fas fa-question-circle"></i>
                    <span>FAQ</span>
                </a>
                @auth('web')
                    <a href="{{ route('profile') }}" class="nav-link">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="login-button">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="login-button">
                        <i class="fas fa-sign-in-alt"></i>
                        Login
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main">
        <div class="container">
            <div class="payment-wrapper">
            <!-- Booking Summary -->
            <div class="booking-summary">
                <h2>Booking Summary</h2>
                
                <div class="summary-card">
                    <div class="room-info">
                        <h3>{{ $booking->room->name }}</h3>
                        <p class="room-type">Max {{ $booking->room->max_guests }} guests</p>
                    </div>
                    
                    <div class="booking-details">
                        <div class="detail-row">
                            <span class="label">Booking Code:</span>
                            <span class="value">{{ $booking->booking_code }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Check-in:</span>
                            <span class="value">{{ \Carbon\Carbon::parse($booking->check_in)->format('D, d M Y') }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Check-out:</span>
                            <span class="value">{{ \Carbon\Carbon::parse($booking->check_out)->format('D, d M Y') }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Nights:</span>
                            <span class="value">{{ \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out)) }} night(s)</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Guests:</span>
                            <span class="value">{{ $booking->guests }} guest(s)</span>
                        </div>
                        @if($booking->extra_bed)
                        <div class="detail-row">
                            <span class="label">Extra Bed:</span>
                            <span class="value">Yes</span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="price-breakdown">
                        <div class="price-row">
                            <span class="label">Total Amount:</span>
                            <span class="value total-price">Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="payment-section">
                <h2>Choose Payment Method</h2>
                
                <div class="payment-card">
                    <!-- Payment Info -->
                    <div class="payment-info">
                        <div class="info-banner">
                            <i class="fas fa-shield-alt"></i>
                            <div>
                                <strong>Simple Payment Process</strong>
                                <p>Choose your payment method and follow the instructions to complete your booking</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Selection Form -->
                    <form action="{{ route('payment.process', $booking->id) }}" method="POST" class="payment-form">
                        @csrf
                        <div class="payment-method-selection">
                            <h3>Select Payment Method:</h3>
                            
                            <div class="payment-options">
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="bank_transfer" required>
                                    <div class="option-content">
                                        <div class="option-header">
                                            <i class="fas fa-university"></i>
                                            <span>Bank Transfer</span>
                                        </div>
                                        <div class="option-details">
                                            <p>Transfer to our bank account and upload proof of payment</p>
                                            <div class="bank-info">
                                                <strong>Bank BCA: 1234567890</strong><br>
                                                <strong>A.n: Bayang Brothers</strong>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="cash" required>
                                    <div class="option-content">
                                        <div class="option-header">
                                            <i class="fas fa-money-bill-wave"></i>
                                            <span>Cash Payment</span>
                                        </div>
                                        <div class="option-details">
                                            <p>Pay directly at our office or upon check-in</p>
                                            <div class="office-info">
                                                <strong>Office Address:</strong><br>
                                                Jl. Contoh No. 123, Yogyakarta
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="digital_wallet" required>
                                    <div class="option-content">
                                        <div class="option-header">
                                            <i class="fas fa-mobile-alt"></i>
                                            <span>Digital Wallet</span>
                                        </div>
                                        <div class="option-details">
                                            <p>Pay using GoPay, DANA, OVO, or ShopeePay</p>
                                            <div class="wallet-info">
                                                <strong>Contact us for wallet payment details</strong>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="payment-notes-section">
                            <label for="payment_note">Additional Notes (Optional):</label>
                            <textarea name="payment_note" id="payment_note" rows="3" placeholder="Add any special requests or notes..."></textarea>
                        </div>

                        <div class="action-buttons">
                            <a href="{{ route('room.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Back to Rooms
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check"></i>
                                Confirm Payment Method
                            </button>
                        </div>
                    </form>

                    <div class="payment-notes">
                        <p><i class="fas fa-info-circle"></i> Complete your payment using the selected method</p>
                        <p><i class="fas fa-clock"></i> Payment must be completed within 24 hours</p>
                        <p><i class="fas fa-envelope"></i> Confirmation will be sent to {{ $booking->user->email }}</p>
                        <p><i class="fas fa-phone"></i> Contact us at +62 813-9264-0030 for assistance</p>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <h3 class="footer-title">Bayang Brothers</h3>
            <p class="footer-description">Bayang Brothers is a booking room service operating in Yogyakarta.</p>

            <div class="footer-bottom">
                <p class="footer-copyright">Copyright ©2025 Bayang Brothers</p>
                <div class="social-media-container">
                    <a href="tel:+6281392640030" class="social-link">
                        <i class="fas fa-phone"></i>
                    </a>
                    <a href="https://instagram.com/bayangbrothers" class="social-link">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://wa.me/6281392640030" class="social-link">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Payment Script -->
    <script>
        // Form validation and interaction
        document.addEventListener('DOMContentLoaded', function() {
            const paymentOptions = document.querySelectorAll('input[name="payment_method"]');
            const submitButton = document.querySelector('button[type="submit"]');
            
            // Enable submit button when payment method is selected
            paymentOptions.forEach(option => {
                option.addEventListener('change', function() {
                    submitButton.disabled = false;
                });
            });
            
            // Form submission handling
            document.querySelector('.payment-form').addEventListener('submit', function(e) {
                const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
                if (!selectedMethod) {
                    e.preventDefault();
                    alert('Please select a payment method');
                    return;
                }
                
                // Disable submit button to prevent double submission
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            });
        });

        // Auto-disable payment after 24 hours
        const bookingTime = new Date('{{ $booking->created_at }}');
        const expiryTime = new Date(bookingTime.getTime() + (24 * 60 * 60 * 1000)); // 24 hours
        const now = new Date();
        
        if (now > expiryTime) {
            const form = document.querySelector('.payment-form');
            const submitButton = document.querySelector('button[type="submit"]');
            const paymentOptions = document.querySelectorAll('input[name="payment_method"]');
            
            form.style.opacity = '0.5';
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-times"></i> Payment Expired';
            submitButton.classList.add('expired');
            
            paymentOptions.forEach(option => {
                option.disabled = true;
            });
        }

        // Show success/error messages
        @if(session('success'))
            setTimeout(() => {
                alert('{{ session('success') }}');
            }, 100);
        @endif
        
        @if(session('error'))
            setTimeout(() => {
                alert('{{ session('error') }}');
            }, 100);
        @endif
    </script>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif
</body>
</html>
