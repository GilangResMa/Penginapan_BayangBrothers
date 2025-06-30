<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Bayang Brothers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/payment.css'])
    <script type="text/javascript"
            src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.stg.midtrans.com/snap/snap.js' }}"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
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
            <!-- Page Title -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-credit-card"></i>
                    Payment Confirmation
                </h1>
                <p class="page-subtitle">Review your booking details and proceed with payment</p>
            </div>

            <!-- Payment Content -->
            <div class="payment-wrapper">
                <!-- Booking Summary Card -->
                <div class="booking-summary-card">
                    <div class="card-header">
                        <h2>
                            <i class="fas fa-file-invoice"></i>
                            Booking Summary
                        </h2>
                        <span class="booking-status pending">Pending Payment</span>
                    </div>
                    
                    <div class="room-preview">
                        <div class="room-image">
                            <img src="{{ asset('img/kamar1.jpg') }}" alt="{{ $booking->room->name }}">
                        </div>
                        <div class="room-details">
                            <h3 class="room-name">{{ $booking->room->name }}</h3>
                            <p class="room-description">
                                {{ $booking->room->description ?? 'Comfortable room with modern amenities including AC, WiFi, TV, and private bathroom.' }}
                            </p>
                            <div class="room-facilities">
                                <span class="facility"><i class="fas fa-snowflake"></i> AC</span>
                                <span class="facility"><i class="fas fa-tv"></i> TV</span>
                                <span class="facility"><i class="fas fa-wifi"></i> WiFi</span>
                                <span class="facility"><i class="fas fa-bath"></i> Private Bath</span>
                            </div>
                        </div>
                    </div>

                    <div class="booking-info">
                        <div class="info-grid">
                            <div class="info-item">
                                <i class="fas fa-barcode"></i>
                                <div>
                                    <label>Booking Code</label>
                                    <span>{{ $booking->booking_code }}</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-calendar-check"></i>
                                <div>
                                    <label>Check-in</label>
                                    <span>{{ \Carbon\Carbon::parse($booking->check_in)->format('D, d M Y') }}</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-calendar-times"></i>
                                <div>
                                    <label>Check-out</label>
                                    <span>{{ \Carbon\Carbon::parse($booking->check_out)->format('D, d M Y') }}</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-moon"></i>
                                <div>
                                    <label>Duration</label>
                                    <span>{{ \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out)) }} night(s)</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-users"></i>
                                <div>
                                    <label>Guests</label>
                                    <span>{{ $booking->guests }} {{ $booking->guests > 1 ? 'guests' : 'guest' }}</span>
                                </div>
                            </div>
                            @if($booking->extra_bed)
                            <div class="info-item">
                                <i class="fas fa-bed"></i>
                                <div>
                                    <label>Extra Bed</label>
                                    <span>Included</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="price-breakdown">
                        <h3><i class="fas fa-calculator"></i> Price Breakdown</h3>
                        <div class="price-details">
                            @php
                                $checkin = \Carbon\Carbon::parse($booking->check_in);
                                $checkout = \Carbon\Carbon::parse($booking->check_out);
                                $totalNights = $checkin->diffInDays($checkout);
                                $weekdayNights = 0;
                                $weekendNights = 0;
                                
                                $current = $checkin->copy();
                                while ($current->lt($checkout)) {
                                    if ($current->isWeekend()) {
                                        $weekendNights++;
                                    } else {
                                        $weekdayNights++;
                                    }
                                    $current->addDay();
                                }
                                
                                $roomCost = 0;
                                if ($weekdayNights > 0) {
                                    $roomCost += $weekdayNights * ($booking->room->price_weekday ?? 150000);
                                }
                                if ($weekendNights > 0) {
                                    $roomCost += $weekendNights * ($booking->room->price_weekend ?? 180000);
                                }
                                
                                $extraBedCost = 0;
                                if ($booking->extra_bed) {
                                    $extraBedCost = $totalNights * ($booking->room->extra_bed_price ?? 70000);
                                }
                            @endphp
                            
                            @if($weekdayNights > 0)
                            <div class="price-item">
                                <span>Room cost ({{ $weekdayNights }} weekday{{ $weekdayNights > 1 ? 's' : '' }})</span>
                                <span>Rp {{ number_format($weekdayNights * ($booking->room->price_weekday ?? 150000), 0, ',', '.') }}</span>
                            </div>
                            @endif
                            
                            @if($weekendNights > 0)
                            <div class="price-item">
                                <span>Room cost ({{ $weekendNights }} weekend{{ $weekendNights > 1 ? 's' : '' }})</span>
                                <span>Rp {{ number_format($weekendNights * ($booking->room->price_weekend ?? 180000), 0, ',', '.') }}</span>
                            </div>
                            @endif
                            
                            @if($booking->extra_bed)
                            <div class="price-item">
                                <span>Extra bed ({{ $totalNights }} night{{ $totalNights > 1 ? 's' : '' }})</span>
                                <span>Rp {{ number_format($extraBedCost, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            
                            <div class="price-divider"></div>
                            <div class="price-total">
                                <span>Total Amount</span>
                                <span>Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods Card -->
                <div class="payment-methods-card">
                    <div class="card-header">
                        <h2>
                            <i class="fas fa-credit-card"></i>
                            Payment Methods
                        </h2>
                        <p>Choose your preferred payment method</p>
                    </div>

                    <!-- Payment Options Info -->
                    <div class="payment-info">
                        <div class="info-banner">
                            <i class="fas fa-shield-alt"></i>
                            <div>
                                <strong>Secure Payment</strong>
                                <p>Your payment is processed securely through Midtrans payment gateway</p>
                            </div>
                        </div>

                        <div class="available-methods">
                            <h3>Available Payment Methods in Indonesia:</h3>
                            <div class="methods-grid">
                                <!-- Credit/Debit Cards -->
                                <div class="method-category">
                                    <h4><i class="fas fa-credit-card"></i> Credit & Debit Cards</h4>
                                    <div class="method-items">
                                        <span class="method-item"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSiD0FD3k7rC2_9o3OHlSHuJGQVDYCy7KZ3ng&s" alt="Visa"> Visa</span>
                                        <span class="method-item"><img src="https://logos-world.net/wp-content/uploads/2020/04/Mastercard-Logo.png" alt="Mastercard"> Mastercard</span>
                                        <span class="method-item"><img src="https://e7.pngegg.com/pngimages/178/595/png-clipart-jcb-co-ltd-logo-credit-card-jcb-text-orange.png" alt="JCB"> JCB</span>
                                    </div>
                                </div>

                                <!-- Bank Transfer -->
                                <div class="method-category">
                                    <h4><i class="fas fa-university"></i> Bank Transfer</h4>
                                    <div class="method-items">
                                        <span class="method-item">BCA</span>
                                        <span class="method-item">BNI</span>
                                        <span class="method-item">BRI</span>
                                        <span class="method-item">Mandiri</span>
                                        <span class="method-item">CIMB Niaga</span>
                                        <span class="method-item">Permata</span>
                                    </div>
                                </div>

                                <!-- E-Wallets -->
                                <div class="method-category">
                                    <h4><i class="fas fa-mobile-alt"></i> E-Wallets</h4>
                                    <div class="method-items">
                                        <span class="method-item">GoPay</span>
                                        <span class="method-item">ShopeePay</span>
                                        <span class="method-item">DANA</span>
                                        <span class="method-item">OVO</span>
                                        <span class="method-item">LinkAja</span>
                                    </div>
                                </div>

                                <!-- Convenience Stores -->
                                <div class="method-category">
                                    <h4><i class="fas fa-store"></i> Convenience Stores</h4>
                                    <div class="method-items">
                                        <span class="method-item">Indomaret</span>
                                        <span class="method-item">Alfamart</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Actions -->
                    <div class="payment-actions">
                        <div class="payment-summary">
                            <div class="amount-display">
                                <span class="amount-label">Total to Pay:</span>
                                <span class="amount-value">Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <a href="{{ route('room.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Back to Rooms
                            </a>
                            <button id="pay-button" class="btn btn-primary">
                                <i class="fas fa-lock"></i>
                                Proceed to Payment
                            </button>
                        </div>

                        <div class="payment-notes">
                            <p><i class="fas fa-info-circle"></i> You will be redirected to our secure payment page</p>
                            <p><i class="fas fa-clock"></i> Payment must be completed within 24 hours</p>
                            <p><i class="fas fa-envelope"></i> Confirmation will be sent to {{ $booking->user->email }}</p>
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
        // Payment button functionality
        document.getElementById('pay-button').addEventListener('click', function () {
            // Disable button to prevent double clicks
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            
            // Trigger Midtrans Snap
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    console.log('Payment success:', result);
                    window.location.href = '{{ route("payment.finish") }}?order_id=' + result.order_id + 
                                          '&status_code=' + result.status_code + 
                                          '&transaction_status=' + result.transaction_status;
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    window.location.href = '{{ route("payment.unfinish") }}?order_id=' + result.order_id;
                },
                onError: function(result) {
                    console.log('Payment error:', result);
                    window.location.href = '{{ route("payment.error") }}?order_id=' + result.order_id;
                },
                onClose: function() {
                    console.log('Payment popup closed');
                    // Re-enable button if popup is closed
                    document.getElementById('pay-button').disabled = false;
                    document.getElementById('pay-button').innerHTML = '<i class="fas fa-lock"></i> Proceed to Payment';
                }
            });
        });

        // Auto-disable payment button after 24 hours
        const bookingTime = new Date('{{ $booking->created_at }}');
        const expiryTime = new Date(bookingTime.getTime() + (24 * 60 * 60 * 1000)); // 24 hours
        const now = new Date();
        
        if (now > expiryTime) {
            document.getElementById('pay-button').disabled = true;
            document.getElementById('pay-button').innerHTML = '<i class="fas fa-times"></i> Payment Expired';
            document.getElementById('pay-button').classList.add('expired');
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
</body>
</html>
