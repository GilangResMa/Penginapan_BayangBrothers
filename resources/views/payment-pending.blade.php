<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment Pending - Bayang Brothers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/payment.css'])
    <style>
        .pending-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .pending-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            text-align: center;
            margin: 30px 0;
        }
        .pending-icon {
            font-size: 64px;
            color: #f39c12;
            margin-bottom: 20px;
        }
        .pending-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .pending-subtitle {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }
        .booking-summary-pending {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }
        .timeline {
            max-width: 600px;
            margin: 30px auto;
        }
        .timeline-item {
            display: flex;
            align-items: center;
            margin: 20px 0;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .timeline-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 18px;
        }
        .timeline-icon.completed {
            background: #28a745;
            color: white;
        }
        .timeline-icon.current {
            background: #f39c12;
            color: white;
        }
        .timeline-icon.pending {
            background: #e9ecef;
            color: #6c757d;
        }
        .timeline-content h4 {
            margin: 0 0 5px 0;
            font-size: 16px;
            font-weight: bold;
        }
        .timeline-content p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
        .contact-info {
            background: #e3f2fd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .contact-info h4 {
            margin: 0 0 10px 0;
            color: #1976d2;
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
            <div class="payment-status-page">
                <!-- Status Icon -->
                <div class="status-icon pending">
                    <i class="fas fa-clock"></i>
                </div>

                <!-- Status Message -->
                <div class="status-message">
                    <h1>Payment Pending</h1>
                    <p>Your payment is being processed. Please follow the instructions below to complete your payment.</p>
                </div>

                <!-- Booking Details -->
                <div class="booking-summary">
                    <h2>Booking Details</h2>
                    
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
                                <span class="label">Total Amount:</span>
                                <span class="value total-price">Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Status:</span>
                                <span class="value status-pending">{{ ucfirst($booking->status) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Instructions -->
                <div class="payment-instructions">
                    <h3><i class="fas fa-info-circle"></i> Payment Instructions</h3>
                    
                    @if($booking->payment_method === 'bank_transfer')
                        <div class="instruction-card">
                            <h4>Bank Transfer Instructions</h4>
                            <ol>
                                <li>Complete the bank transfer using your preferred method</li>
                                <li>Make sure to use the Virtual Account number provided</li>
                                <li>Payment will be automatically confirmed once received</li>
                                <li>You will receive confirmation via email</li>
                            </ol>
                        </div>
                    @elseif($booking->payment_method === 'digital_wallet')
                        <div class="instruction-card">
                            <h4>Digital Wallet Instructions</h4>
                            <ol>
                                <li>Open your digital wallet app (GoPay, ShopeePay, etc.)</li>
                                <li>Scan the QR code provided or enter the payment details</li>
                                <li>Complete the payment in your app</li>
                                <li>Payment confirmation will be sent automatically</li>
                            </ol>
                        </div>
                    @else
                        <div class="instruction-card">
                            <h4>Payment Instructions</h4>
                            <ol>
                                <li>Complete your payment using the selected method</li>
                                <li>Follow the instructions provided by your payment provider</li>
                                <li>Payment confirmation will be processed automatically</li>
                                <li>You will receive notification once payment is confirmed</li>
                            </ol>
                        </div>
                    @endif

                    <div class="important-notes">
                        <h4><i class="fas fa-exclamation-triangle"></i> Important Notes</h4>
                        <ul>
                            <li>Payment must be completed within 24 hours</li>
                            <li>Keep this page open or bookmark it for reference</li>
                            <li>Contact customer service if you encounter any issues</li>
                            <li>Your booking will be automatically confirmed once payment is received</li>
                        </ul>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('profile') }}" class="btn btn-primary">
                        <i class="fas fa-user"></i>
                        View My Bookings
                    </a>
                    <a href="{{ route('room.index') }}" class="btn btn-secondary">
                        <i class="fas fa-bed"></i>
                        Browse Rooms
                    </a>
                </div>

                <!-- Contact Information -->
                <div class="contact-info">
                    <h4><i class="fas fa-headset"></i> Need Help?</h4>
                    <p>Contact our customer service team:</p>
                    <div class="contact-methods">
                        <a href="tel:+6281392640030" class="contact-method">
                            <i class="fas fa-phone"></i>
                            <span>+62 813-9264-0030</span>
                        </a>
                        <a href="https://wa.me/6281392640030" class="contact-method" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                            <span>WhatsApp Support</span>
                        </a>
                        <a href="mailto:support@bayangbrothers.com" class="contact-method">
                            <i class="fas fa-envelope"></i>
                            <span>support@bayangbrothers.com</span>
                        </a>
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

    <!-- Auto-refresh script -->
    <script>
        // Auto-refresh page every 30 seconds to check payment status
        setTimeout(function() {
            location.reload();
        }, 30000);

        // Show any session messages
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

    <style>
        .payment-status-page {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            text-align: center;
        }

        .status-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 2rem;
            color: white;
        }

        .status-icon.pending {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .status-message h1 {
            color: #f59e0b;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .status-message p {
            color: #6b7280;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .payment-instructions {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 2rem;
            margin: 2rem 0;
            text-align: left;
        }

        .payment-instructions h3 {
            color: #dc2626;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .instruction-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .instruction-card h4 {
            color: #374151;
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .instruction-card ol {
            color: #6b7280;
            padding-left: 1.5rem;
        }

        .instruction-card ol li {
            margin-bottom: 0.5rem;
        }

        .important-notes {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 1.5rem;
        }

        .important-notes h4 {
            color: #92400e;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .important-notes ul {
            color: #92400e;
            padding-left: 1.5rem;
        }

        .important-notes ul li {
            margin-bottom: 0.5rem;
        }

        .contact-info {
            background: #f0f9ff;
            border-radius: 12px;
            padding: 2rem;
            margin: 2rem 0;
        }

        .contact-info h4 {
            color: #1e40af;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: center;
        }

        .contact-methods {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        .contact-method {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            background: white;
            border-radius: 8px;
            text-decoration: none;
            color: #1e40af;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .contact-method:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            color: #1d4ed8;
        }

        .status-pending {
            color: #f59e0b !important;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .payment-status-page {
                padding: 1rem;
            }

            .status-message h1 {
                font-size: 2rem;
            }

            .contact-methods {
                flex-direction: column;
                align-items: center;
            }

            .contact-method {
                width: 100%;
                justify-content: center;
                max-width: 300px;
            }
        }
    </style>
</body>
</html>
