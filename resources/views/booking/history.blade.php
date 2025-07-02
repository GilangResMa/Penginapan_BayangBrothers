<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/profile.css'])
    <style>
        .booking-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 15px;
        }
        .action-btn.danger {
            background: #dc3545;
            color: white;
            border: 2px solid #dc3545;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .action-btn.danger:hover {
            background: #c82333;
            border-color: #c82333;
            transform: translateY(-1px);
        }
        .action-btn.warning {
            background: #ffc107;
            color: #212529;
            border: 2px solid #ffc107;
        }
        .action-btn.warning:hover {
            background: #e0a800;
            border-color: #e0a800;
        }
        .action-btn.success {
            background: #28a745;
            color: white;
            border: 2px solid #28a745;
        }
        .action-btn.info {
            background: #17a2b8;
            color: white;
            border: 2px solid #17a2b8;
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

                            @if ($booking->extra_bed)
                                <div class="extra-info">
                                    <i class="fas fa-bed"></i>
                                    <span>Extra Bed</span>
                                </div>
                            @endif

                            <div class="booking-footer">
                                <div class="total-cost">
                                    <span class="cost-label">Total:</span>
                                    <span class="cost-value">Rp
                                        {{ number_format($booking->total_cost, 0, ',', '.') }}</span>
                                </div>

                                <div class="booking-date">
                                    <small>Dibuat: {{ $booking->created_at->format('d M Y, H:i') }}</small>
                                </div>
                            </div>

                            @if ($booking->status === 'pending')
                                <div class="booking-actions">
                                    <a href="{{ route('payment', $booking->id) }}" class="action-btn primary">
                                        <i class="fas fa-credit-card"></i>
                                        Lanjutkan Pembayaran
                                    </a>
                                    <form method="POST" action="{{ route('booking.cancel', $booking->id) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn danger" 
                                                onclick="return confirmCancel(event, '{{ $booking->booking_code }}', 'pending')"
                                                title="Batalkan Booking">
                                            <i class="fas fa-times"></i>
                                            Batalkan
                                        </button>
                                    </form>
                                </div>
                            @elseif ($booking->status === 'awaiting_payment')
                                <div class="booking-actions">
                                    <a href="{{ route('payment.pending', $booking->id) }}" class="action-btn warning">
                                        <i class="fas fa-clock"></i>
                                        Lihat Status Verifikasi
                                    </a>
                                    <form method="POST" action="{{ route('booking.cancel', $booking->id) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn danger" 
                                                onclick="return confirmCancel(event, '{{ $booking->booking_code }}', 'awaiting_payment')"
                                                title="Batalkan Booking">
                                            <i class="fas fa-times"></i>
                                            Batalkan
                                        </button>
                                    </form>
                                </div>
                            @elseif ($booking->status === 'confirmed')
                                <div class="booking-actions">
                                    <span class="action-btn success" style="cursor: default;">
                                        <i class="fas fa-check-circle"></i>
                                        Booking Terkonfirmasi
                                    </span>
                                </div>
                            @elseif ($booking->status === 'cancelled')
                                <div class="booking-actions">
                                    <span class="action-btn danger" style="cursor: default; opacity: 0.6;">
                                        <i class="fas fa-ban"></i>
                                        Booking Dibatalkan
                                    </span>
                                </div>
                            @elseif ($booking->status === 'completed')
                                <div class="booking-actions">
                                    <span class="action-btn info" style="cursor: default;">
                                        <i class="fas fa-flag-checkered"></i>
                                        Selesai
                                    </span>
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
    </footer>

    <script>
        // Enhanced confirmation for booking cancellation
        function confirmCancel(event, bookingCode, status) {
            event.preventDefault();
            
            let message = `Apakah Anda yakin ingin membatalkan booking ${bookingCode}?`;
            
            if (status === 'awaiting_payment') {
                message += '\n\nPerhatian: Bukti pembayaran yang sudah diupload akan dihapus dan Anda perlu mengupload ulang jika ingin melanjutkan booking.';
            } else {
                message += '\n\nBooking akan dibatalkan dan tidak dapat dikembalikan.';
            }
            
            if (confirm(message)) {
                event.target.closest('form').submit();
            }
            
            return false;
        }

        // Auto hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            if (alerts.length > 0) {
                setTimeout(() => {
                    alerts.forEach(alert => {
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 300);
                    });
                }, 5000);
            }
        });
    </script>
</body>

</html>
