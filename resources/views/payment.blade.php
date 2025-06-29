<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Bayang Brothers</title>
    @vite(['resources/css/payment.css'])
    <script type="text/javascript"
            src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.stg.midtrans.com/snap/snap.js' }}"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <a href="{{ route('homepage') }}">Bayang Brothers</a>
            </div>
            <div class="nav-links">
                <a href="{{ route('homepage') }}">Home</a>
                <a href="{{ route('room') }}">Rooms</a>
                <a href="{{ route('about') }}">About</a>
                <a href="{{ route('faq') }}">FAQ</a>
                @auth('web')
                    <a href="{{ route('profile') }}">Profile</a>
                    <form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
                        @csrf
                        <button type="submit" class="nav-logout-btn">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-login-btn">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Payment Content -->
    <div class="payment-container">
        <div class="payment-wrapper">
            <!-- Booking Summary -->
            <div class="booking-summary">
                <h2>Booking Summary</h2>
                
                <div class="summary-card">
                    <div class="room-info">
                        <h3>{{ $booking->room->name }}</h3>
                        <p class="room-type">{{ $booking->room->type }}</p>
                    </div>
                    
                    <div class="booking-details">
                        <div class="detail-row">
                            <span class="label">Booking Code:</span>
                            <span class="value">{{ $booking->booking_code }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Check-in:</span>
                            <span class="value">{{ $booking->formatted_check_in }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Check-out:</span>
                            <span class="value">{{ $booking->formatted_check_out }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Nights:</span>
                            <span class="value">{{ $booking->nights }} night(s)</span>
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
                <h2>Payment</h2>
                
                <div class="payment-card">
                    <div class="payment-info">
                        <p>Click the button below to proceed with payment using our secure payment gateway.</p>
                        <p class="payment-note">You will be redirected to Midtrans payment page.</p>
                    </div>
                    
                    <button id="pay-button" class="pay-button">
                        Pay Now - Rp {{ number_format($booking->total_cost, 0, ',', '.') }}
                    </button>
                    
                    <div class="payment-methods">
                        <p>We accept:</p>
                        <div class="method-icons">
                            <span class="method">Credit Card</span>
                            <span class="method">Bank Transfer</span>
                            <span class="method">E-Wallet</span>
                            <span class="method">Convenience Store</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Bayang Brothers Hotel</h3>
                    <p>Experience luxury and comfort in the heart of the city.</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="{{ route('homepage') }}">Home</a></li>
                        <li><a href="{{ route('room') }}">Rooms</a></li>
                        <li><a href="{{ route('about') }}">About</a></li>
                        <li><a href="{{ route('faq') }}">FAQ</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact Info</h4>
                    <p>📍 123 Luxury Street, City Center</p>
                    <p>📞 +62 123 456 7890</p>
                    <p>✉️ info@bayangbrothers.com</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Bayang Brothers Hotel. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Payment Script -->
    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
            window.snap.pay('{{ $snapToken }}', {
                // Optional
                onSuccess: function(result) {
                    /* You may add your own js here, this is just example */
                    console.log(result);
                    window.location.href = '{{ route('payment.finish') }}?' + new URLSearchParams(result).toString();
                },
                // Optional
                onPending: function(result) {
                    /* You may add your own js here, this is just example */
                    console.log(result);
                    window.location.href = '{{ route('payment.finish') }}?' + new URLSearchParams(result).toString();
                },
                // Optional
                onError: function(result) {
                    /* You may add your own js here, this is just example */
                    console.log(result);
                    window.location.href = '{{ route('payment.error') }}?' + new URLSearchParams(result).toString();
                }
            });
        });
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
