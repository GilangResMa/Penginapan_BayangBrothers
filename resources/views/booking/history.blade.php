<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History - Bayang Brothers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/profile.css'])
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
                <a href="{{ route('profile') }}" class="nav-link active">
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
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="booking-history-container">
                <div class="page-header">
                    <h1><i class="fas fa-history"></i> Riwayat Booking</h1>
                    <p>Lihat semua booking yang pernah Anda lakukan</p>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @forelse ($bookings as $booking)
                    <div class="booking-card">
                        <div class="booking-header">
                            <div class="booking-info">
                                <h3>{{ $booking->room->name }}</h3>
                                <p class="booking-code">Kode: {{ $booking->booking_code }}</p>
                            </div>
                            <div class="booking-status">
                                <span class="status-badge {{ $booking->status_badge }}">
                                    {{ $booking->status_text }}
                                </span>
                            </div>
                        </div>

                        <div class="booking-details">
                            <div class="detail-row">
                                <div class="detail-item">
                                    <i class="fas fa-calendar"></i>
                                    <div>
                                        <span class="label">Check-in</span>
                                        <span class="value">{{ $booking->formatted_check_in }}</span>
                                    </div>
                                </div>
                                
                                <div class="detail-item">
                                    <i class="fas fa-calendar"></i>
                                    <div>
                                        <span class="label">Check-out</span>
                                        <span class="value">{{ $booking->formatted_check_out }}</span>
                                    </div>
                                </div>
                                
                                <div class="detail-item">
                                    <i class="fas fa-moon"></i>
                                    <div>
                                        <span class="label">Malam</span>
                                        <span class="value">{{ $booking->nights }}</span>
                                    </div>
                                </div>
                                
                                <div class="detail-item">
                                    <i class="fas fa-users"></i>
                                    <div>
                                        <span class="label">Tamu</span>
                                        <span class="value">{{ $booking->guests }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($booking->extra_bed)
                                <div class="extra-info">
                                    <i class="fas fa-bed"></i>
                                    <span>Extra Bed</span>
                                </div>
                            @endif

                            <div class="booking-footer">
                                <div class="total-cost">
                                    <span class="cost-label">Total:</span>
                                    <span class="cost-value">Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</span>
                                </div>
                                
                                <div class="booking-date">
                                    <small>Dibuat: {{ $booking->created_at->format('d M Y, H:i') }}</small>
                                </div>
                            </div>

                            @if($booking->status === 'pending')
                                <div class="booking-actions">
                                    <a href="{{ route('payment', $booking->id) }}" class="action-btn primary">
                                        <i class="fas fa-credit-card"></i>
                                        Lanjutkan Pembayaran
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <h3>Belum Ada Booking</h3>
                        <p>Anda belum memiliki riwayat booking. Mulai booking sekarang!</p>
                        <a href="{{ route('room.index') }}" class="action-btn primary">
                            <i class="fas fa-bed"></i>
                            Lihat Kamar
                        </a>
                    </div>
                @endforelse

                <div class="back-to-profile">
                    <a href="{{ route('profile') }}" class="action-btn secondary">
                        <i class="fas fa-arrow-left"></i>
                        Kembali ke Profile
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
