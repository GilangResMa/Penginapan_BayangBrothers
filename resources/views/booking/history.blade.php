<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History - Bayang Brothers</title>
    @vite(['resources/css/profile.css'])
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
                <a href="{{ route('room.index') }}">Rooms</a>
                <a href="{{ route('about') }}">About</a>
                <a href="{{ route('faq') }}">FAQ</a>
                <a href="{{ route('profile') }}">Profile</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="nav-logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main">
        <div class="container">
            <div class="booking-history-container">
                <div class="page-header">
                    <h1>Booking History</h1>
                    <p>Lihat semua booking yang pernah Anda lakukan</p>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error">
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
</body>

</html>
