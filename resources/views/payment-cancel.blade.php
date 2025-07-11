<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Cancelled</title>
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
                    <span>Rooms</span>
                </a>
                <a href="{{ route('about') }}" class="nav-link">
                    <i class="fas fa-info-circle"></i>
                    <span>About</span>
                </a>
                <a href="{{ route('faq') }}" class="nav-link">
                    <i class="fas fa-question-circle"></i>
                    <span>FAQ</span>
                </a>
                @auth
                    <a href="{{ route('profile') }}" class="nav-link">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="login-button">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="login-button">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="payment-container">
            <!-- Cancel Message -->
            <div class="cancel-container">
                <div class="cancel-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <h1 class="cancel-title">Pembayaran Dibatalkan</h1>
                <p class="cancel-subtitle">Booking Anda belum selesai. Jangan khawatir, data Anda masih tersimpan.</p>
            </div>

            <!-- Booking Details -->
            <div class="booking-summary">
                <h2 class="summary-title">
                    <i class="fas fa-file-alt"></i>
                    Detail Booking
                </h2>
                
                <div class="summary-content">
                    <div class="booking-info-row">
                        <span class="info-label">Kode Booking:</span>
                        <span class="info-value booking-code">{{ $booking->booking_code }}</span>
                    </div>
                    
                    <div class="booking-info-row">
                        <span class="info-label">Kamar:</span>
                        <span class="info-value">{{ $booking->room->name }}</span>
                    </div>
                    
                    <div class="booking-info-row">
                        <span class="info-label">Check-in:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</span>
                    </div>
                    
                    <div class="booking-info-row">
                        <span class="info-label">Check-out:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}</span>
                    </div>
                    
                    <div class="booking-info-row">
                        <span class="info-label">Jumlah Malam:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($booking->check_in_date)->diffInDays(\Carbon\Carbon::parse($booking->check_out_date)) }} malam</span>
                    </div>
                    
                    <div class="booking-info-row">
                        <span class="info-label">Jumlah Tamu:</span>
                        <span class="info-value">{{ $booking->guests }} orang</span>
                    </div>
                    
                    @if($booking->extra_bed)
                    <div class="booking-info-row">
                        <span class="info-label">Extra Bed:</span>
                        <span class="info-value">Ya</span>
                    </div>
                    @endif
                    
                    <div class="booking-info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value status-pending">Pending</span>
                    </div>
                    
                    <div class="booking-info-row total-row">
                        <span class="info-label">Total Pembayaran:</span>
                        <span class="info-value total-amount">Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- What's Next -->
            <div class="next-steps">
                <h3 class="steps-title">
                    <i class="fas fa-question-circle"></i>
                    Apa yang Terjadi Selanjutnya?
                </h3>
                <div class="steps-content">
                    <div class="step-item">
                        <i class="fas fa-save"></i>
                        <div class="step-text">
                            <strong>Data Tersimpan</strong>
                            <p>Booking Anda masih dalam status pending dan data tersimpan dengan aman.</p>
                        </div>
                    </div>
                    
                    <div class="step-item">
                        <i class="fas fa-credit-card"></i>
                        <div class="step-text">
                            <strong>Lanjutkan Pembayaran</strong>
                            <p>Anda dapat melanjutkan pembayaran kapan saja melalui halaman riwayat booking.</p>
                        </div>
                    </div>
                    
                    <div class="step-item">
                        <i class="fas fa-clock"></i>
                        <div class="step-text">
                            <strong>Batas Waktu</strong>
                            <p>Harap selesaikan pembayaran dalam 24 jam untuk memastikan ketersediaan kamar.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('payment', $booking->id) }}" class="btn btn-primary">
                    <i class="fas fa-credit-card"></i>
                    Lanjutkan Pembayaran
                </a>
                
                <a href="{{ route('booking.history') }}" class="btn btn-secondary">
                    <i class="fas fa-history"></i>
                    Lihat Riwayat Booking
                </a>
                
                <a href="{{ route('homepage') }}" class="btn btn-outline">
                    <i class="fas fa-home"></i>
                    Kembali ke Beranda
                </a>
            </div>

            <!-- Contact Information -->
            <div class="contact-info">
                <h4 class="contact-title">
                    <i class="fas fa-headset"></i>
                    Butuh Bantuan?
                </h4>
                <div class="contact-details">
                    <a href="tel:+6281392640030" class="contact-link">
                        <i class="fas fa-phone"></i>
                        +62 813-9264-0030
                    </a>
                    <a href="https://wa.me/6281392640030" class="contact-link">
                        <i class="fab fa-whatsapp"></i>
                        WhatsApp
                    </a>
                    <a href="https://instagram.com/bayangbrothers" class="contact-link">
                        <i class="fab fa-instagram"></i>
                        @bayangbrothers
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3 class="footer-title">Bayang Brothers</h3>
                <p class="footer-description">
                    Bayang Brothers is a booking room service operating in Yogyakarta.
                </p>
            </div>

            <div class="footer-section">
                <h4 class="footer-subtitle">Quick Links</h4>
                <div class="footer-links">
                    <a href="{{ route('homepage') }}" class="footer-link">Home</a>
                    <a href="{{ route('room.index') }}" class="footer-link">Rooms</a>
                    <a href="{{ route('about') }}" class="footer-link">About</a>
                    <a href="{{ route('faq') }}" class="footer-link">FAQ</a>
                </div>
            </div>

            <div class="footer-section">
                <h4 class="footer-subtitle">Contact Us</h4>
                <div class="footer-contact">
                    <a href="tel:+6281392640030" class="footer-contact-item">
                        <i class="fas fa-phone"></i>
                        +62 813-9264-0030
                    </a>
                    <a href="https://wa.me/6281392640030" class="footer-contact-item">
                        <i class="fab fa-whatsapp"></i>
                        WhatsApp
                    </a>
                    <a href="https://instagram.com/bayangbrothers" class="footer-contact-item">
                        <i class="fab fa-instagram"></i>
                        @bayangbrothers
                    </a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p class="footer-copyright">Copyright ©2025 Bayang Brothers</p>
        </div>
    </footer>
</body>
</html>
