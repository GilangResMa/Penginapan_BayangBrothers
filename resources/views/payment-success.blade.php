<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success - Bayang Brothers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/payment.css'])
    <style>
        .success-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        .success-icon {
            font-size: 4rem;
            color: #27ae60;
            margin-bottom: 1rem;
        }
        .success-title {
            color: #2c3e50;
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .payment-method-info {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 1.5rem 0;
            text-align: left;
        }
        .method-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1rem;
            text-align: center;
        }
        .bank-details, .office-details, .wallet-details {
            background: #e8f4fd;
            padding: 1rem;
            border-radius: 6px;
            margin: 1rem 0;
        }
        .booking-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 6px;
            margin: 1rem 0;
            text-align: left;
        }
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-primary {
            background-color: #3498db;
            color: white;
        }
        .btn-secondary {
            background-color: #95a5a6;
            color: white;
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .important-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 1rem;
            border-radius: 6px;
            margin: 1rem 0;
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
            <div class="success-container">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                
                <h1 class="success-title">Payment Method Selected Successfully!</h1>
                
                <p>Terima kasih! Metode pembayaran Anda telah berhasil dipilih. Silakan lakukan pembayaran sesuai dengan instruksi di bawah ini.</p>
                
                <!-- Booking Info -->
                <div class="booking-info">
                    <h3><i class="fas fa-file-invoice"></i> Booking Details</h3>
                    <p><strong>Booking Code:</strong> {{ $booking->booking_code }}</p>
                    <p><strong>Room:</strong> {{ $booking->room->name }}</p>
                    <p><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}</p>
                    <p><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}</p>
                    <p><strong>Guests:</strong> {{ $booking->guests }} {{ $booking->guests > 1 ? 'people' : 'person' }}</p>
                    <p><strong>Total Amount:</strong> Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</p>
                </div>

                <!-- Payment Instructions -->
                <div class="payment-method-info">
                    <h3 class="method-title">Payment Instructions</h3>
                    
                    @if($booking->payment_method === 'bank_transfer')
                        <div class="bank-details">
                            <h4><i class="fas fa-university"></i> Bank Transfer</h4>
                            <p><strong>Bank:</strong> BCA</p>
                            <p><strong>Account Number:</strong> 1234567890</p>
                            <p><strong>Account Name:</strong> Bayang Brothers</p>
                            <p><strong>Amount:</strong> Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</p>
                            <div class="important-note">
                                <strong>Important:</strong> Setelah transfer, silakan kirim bukti transfer melalui WhatsApp ke +62 813-9264-0030 beserta booking code <strong>{{ $booking->booking_code }}</strong>
                            </div>
                        </div>
                    @elseif($booking->payment_method === 'cash')
                        <div class="office-details">
                            <h4><i class="fas fa-money-bill-wave"></i> Cash Payment</h4>
                            <p><strong>Office Address:</strong></p>
                            <p>Jl. Contoh No. 123, Yogyakarta</p>
                            <p><strong>Operating Hours:</strong> 09:00 - 17:00 WIB</p>
                            <p><strong>Amount:</strong> Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</p>
                            <div class="important-note">
                                <strong>Alternative:</strong> Anda juga dapat membayar langsung saat check-in. Harap konfirmasi melalui WhatsApp +62 813-9264-0030
                            </div>
                        </div>
                    @elseif($booking->payment_method === 'digital_wallet')
                        <div class="wallet-details">
                            <h4><i class="fas fa-mobile-alt"></i> Digital Wallet</h4>
                            <p>Silakan hubungi kami melalui WhatsApp untuk mendapatkan detail pembayaran digital wallet:</p>
                            <p><strong>WhatsApp:</strong> +62 813-9264-0030</p>
                            <p><strong>Amount:</strong> Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</p>
                            <div class="important-note">
                                <strong>Available:</strong> GoPay, DANA, OVO, ShopeePay
                            </div>
                        </div>
                    @endif

                    @if($booking->payment_note)
                        <div class="booking-notes">
                            <h4><i class="fas fa-sticky-note"></i> Your Notes</h4>
                            <p>{{ $booking->payment_note }}</p>
                        </div>
                    @endif
                </div>

                <div class="important-note">
                    <i class="fas fa-clock"></i>
                    <strong>Payment Deadline:</strong> Silakan lakukan pembayaran dalam 24 jam untuk memastikan booking Anda tetap aktif.
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('profile') }}" class="btn btn-primary">
                        <i class="fas fa-user"></i>
                        View My Bookings
                    </a>
                    <a href="https://wa.me/6281392640030?text=Halo,%20saya%20ingin%20konfirmasi%20pembayaran%20untuk%20booking%20{{ $booking->booking_code }}" 
                       target="_blank" class="btn btn-secondary">
                        <i class="fab fa-whatsapp"></i>
                        Contact Admin
                    </a>
                </div>
            </div>
        </div>
    </main>

                <div class="action-buttons">
                    <a href="https://wa.me/6281392640030" class="btn btn-whatsapp" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                        Contact via WhatsApp
                    </a>
                    <a href="{{ route('room.index') }}" class="btn btn-secondary">
                        <i class="fas fa-bed"></i>
                        Browse More Rooms
                    </a>
                    <a href="{{ route('homepage') }}" class="btn btn-primary">
                        <i class="fas fa-home"></i>
                        Back to Home
                    </a>
                </div>

                <div class="important-notes">
                    <h3><i class="fas fa-exclamation-triangle"></i> Important Notes</h3>
                    <ul>
                        <li>Complete your payment within 24 hours to secure your booking</li>
                        <li>Your booking will be confirmed once payment is received</li>
                        <li>Contact us immediately if you encounter any issues</li>
                        <li>Bring a valid ID during check-in</li>
                    </ul>
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

    <style>
        .success-page {
            max-width: 800px;
            margin: 2rem auto;
            text-align: center;
        }

        .success-icon {
            font-size: 4rem;
            color: #27ae60;
            margin-bottom: 1rem;
        }

        .success-title {
            color: #2c3e50;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .success-subtitle {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .booking-details-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            text-align: left;
        }

        .booking-details-card h2 {
            color: #2c3e50;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem;
            border: 1px solid #e1e8ed;
            border-radius: 6px;
        }

        .detail-item.total {
            background-color: #f8f9fa;
            border-color: #27ae60;
            font-weight: 600;
        }

        .booking-code {
            font-family: monospace;
            background-color: #e8f4fd;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-weight: 600;
        }

        .payment-method {
            text-transform: capitalize;
            color: #3498db;
            font-weight: 600;
        }

        .amount {
            color: #27ae60;
            font-weight: 700;
        }

        .payment-instructions {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            text-align: left;
        }

        .payment-instructions h3 {
            color: #2c3e50;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .instruction-card {
            display: flex;
            gap: 1rem;
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid;
        }

        .instruction-card.bank-transfer {
            background-color: #f8f9fa;
            border-left-color: #3498db;
        }

        .instruction-card.cash {
            background-color: #f8f9fa;
            border-left-color: #f39c12;
        }

        .instruction-card.digital-wallet {
            background-color: #f8f9fa;
            border-left-color: #9b59b6;
        }

        .instruction-card i {
            font-size: 2rem;
            color: #3498db;
            margin-top: 0.5rem;
        }

        .instruction-card h4 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .bank-details, .office-details, .wallet-details {
            background-color: white;
            padding: 1rem;
            border-radius: 6px;
            margin: 0.75rem 0;
            border: 1px solid #e1e8ed;
        }

        .note {
            font-style: italic;
            color: #666;
            margin-top: 0.75rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .btn-whatsapp {
            background-color: #25d366;
            color: white;
        }

        .btn-whatsapp:hover {
            background-color: #128c7e;
        }

        .important-notes {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: left;
        }

        .important-notes h3 {
            color: #856404;
            margin-bottom: 1rem;
            text-align: center;
        }

        .important-notes ul {
            list-style: none;
            padding: 0;
        }

        .important-notes li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #ffeaa7;
        }

        .important-notes li:last-child {
            border-bottom: none;
        }

        .important-notes li:before {
            content: "▶ ";
            color: #856404;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .success-page {
                margin: 1rem;
            }

            .details-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .instruction-card {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</body>
</html>
