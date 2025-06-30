<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Bayang Brothers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/payment.css'])
    <style>
        /* Payment Form Styles */
        .payment-form {
            margin-top: 1rem;
        }

        .payment-method-selection {
            margin-bottom: 1.5rem;
        }

        .payment-method-selection h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .payment-options {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .payment-option {
            cursor: pointer;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            padding: 0;
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
        }

        .payment-option:hover {
            border-color: #3498db;
            box-shadow: 0 2px 8px rgba(52, 152, 219, 0.1);
        }

        .payment-option input[type="radio"] {
            display: none;
        }

        .payment-option input[type="radio"]:checked + .option-content {
            background-color: #f8f9fa;
            border-left: 4px solid #3498db;
        }

        .option-content {
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .option-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .option-header i {
            font-size: 1.5rem;
            color: #3498db;
            width: 24px;
            text-align: center;
        }

        .option-header span {
            font-weight: 600;
            color: #2c3e50;
            font-size: 1.1rem;
        }

        .option-details {
            margin-left: 2.25rem;
            color: #666;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .option-details p {
            margin-bottom: 0.5rem;
        }

        .bank-info, .office-info, .wallet-info {
            background-color: #e8f4fd;
            padding: 0.75rem;
            border-radius: 6px;
            margin-top: 0.5rem;
            font-size: 0.85rem;
        }

        .bank-info strong, .office-info strong, .wallet-info strong {
            color: #2c3e50;
        }

        .payment-notes-section {
            margin-bottom: 1.5rem;
        }

        .payment-notes-section label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2c3e50;
            font-weight: 500;
        }

        .payment-notes-section textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e8ed;
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.9rem;
            resize: vertical;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }

        .payment-notes-section textarea:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .payment-notes-section textarea::placeholder {
            color: #999;
        }

        /* Button states */
        .btn[type="submit"]:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            background-color: #95a5a6;
        }

        .btn.expired {
            background-color: #e74c3c !important;
            color: white !important;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .option-content {
                padding: 1rem;
            }
            
            .option-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .option-details {
                margin-left: 0;
                margin-top: 0.5rem;
            }
            
            .bank-info, .office-info, .wallet-info {
                padding: 0.5rem;
                font-size: 0.8rem;
            }
        }

        /* Form submission animation */
        .payment-form.processing {
            opacity: 0.7;
            pointer-events: none;
        }

        .payment-form.processing .btn[type="submit"] {
            background-color: #95a5a6;
        }
    </style>

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
</body>
</html>
