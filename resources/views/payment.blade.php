<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment - Bayang Brothers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/payment.css'])
    <style>
        .payment-method-card {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            margin: 15px 0;
            background: white;
            transition: all 0.3s ease;
        }
        .payment-method-card.selected {
            border-color: #007bff;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
        }
        .qr-code-container {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 15px 0;
        }
        .bank-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .account-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .copy-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        .upload-section {
            margin: 20px 0;
            padding: 20px;
            border: 2px dashed #ccc;
            border-radius: 8px;
            text-align: center;
        }
        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background: #fff3cd;
            border-radius: 8px;
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
                            <span class="value">{{ $booking->extra_bed }}</span>
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
                <h2><i class="fas fa-credit-card"></i> Selesaikan Pembayaran</h2>
                
                <!-- Total Amount Display -->
                <div class="total-amount">
                    Total Pembayaran: Rp{{ number_format($booking->total_cost, 0, ',', '.') }}
                </div>
                
                <form action="{{ route('payment.upload', $booking->id) }}" method="POST" enctype="multipart/form-data" class="payment-form">
                    @csrf
                    
                    <!-- Payment Method Selection -->
                    <div class="payment-methods">
                        <h3>Metode Pembayaran:</h3>
                        
                        <!-- QRIS Option -->
                        <div class="payment-method-card" data-method="qris">
                            <div class="payment-option">
                                <input type="radio" name="payment_method" value="qris" id="qris" required>
                                <label for="qris">
                                    <div class="option-content">
                                        <div class="option-header">
                                            <i class="fas fa-qrcode"></i>
                                            <span>QRIS (Semua E-Wallet & M-Banking)</span>
                                        </div>
                                        <div class="option-details">
                                            <p>Scan QR Code dengan aplikasi: GoPay, DANA, OVO, ShopeePay, Mobile Banking</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="qr-code-container" style="display: none;">
                                <h4>Scan QR Code untuk Pembayaran</h4>
                                <div style="border: 2px solid #000; padding: 20px; display: inline-block; margin: 15px;">
                                    <!-- QR Code akan diletakkan di sini -->
                                    <div style="width: 200px; height: 200px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 14px; color: #666;">
                                        QR Code<br>Bayang Brothers<br>Rp{{ number_format($booking->total_cost, 0, ',', '.') }}
                                    </div>
                                </div>
                                <p><i class="fas fa-info-circle"></i> Buka aplikasi e-wallet/m-banking Anda dan scan QR code di atas</p>
                            </div>
                        </div>

                        <!-- Bank Transfer Option -->
                        <div class="payment-method-card" data-method="transfer">
                            <div class="payment-option">
                                <input type="radio" name="payment_method" value="bank_transfer" id="transfer" required>
                                <label for="transfer">
                                    <div class="option-content">
                                        <div class="option-header">
                                            <i class="fas fa-university"></i>
                                            <span>Transfer Bank</span>
                                        </div>
                                        <div class="option-details">
                                            <p>Transfer ke rekening bank yang tersedia</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="bank-details" style="display: none;">
                                <h4>Informasi Rekening</h4>
                                
                                <div class="account-info">
                                    <div>
                                        <strong>Bank BCA</strong><br>
                                        <span>4561133632</span><br>
                                        <span>A.n. Ribka Sebayang</span>
                                    </div>
                                    <button type="button" class="copy-btn" onclick="copyToClipboard('1234567890')">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                </div>
                                
                                <p style="margin-top: 15px; color: #666; font-size: 14px;">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    Transfer sesuai nominal: <strong>Rp{{ number_format($booking->total_cost, 0, ',', '.') }}</strong>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Bukti Pembayaran -->
                    <div class="upload-section">
                        <h4><i class="fas fa-upload"></i> Upload Bukti Pembayaran</h4>
                        <p>Upload screenshot atau foto bukti transfer/pembayaran Anda</p>
                        
                        <input type="file" name="payment_proof" id="payment_proof" accept="image/*" required style="margin: 15px 0;">
                        
                        <div style="font-size: 12px; color: #666; margin-top: 10px;">
                            <p>• Format yang didukung: JPG, PNG, JPEG</p>
                            <p>• Maksimal ukuran file: 5MB</p>
                            <p>• Pastikan bukti pembayaran terlihat jelas</p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="action-buttons">
                        <a href="{{ route('room.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i>
                            Konfirmasi Pembayaran
                        </button>
                    </div>
                </form>

                <!-- Payment Notes -->
                <div class="payment-notes">
                    <h4>Catatan Penting:</h4>
                    <p><i class="fas fa-clock"></i> Pembayaran harus diselesaikan dalam 24 jam</p>
                    <p><i class="fas fa-shield-alt"></i> Verifikasi pembayaran akan dilakukan oleh admin maksimal 2x24 jam</p>
                    <p><i class="fas fa-envelope"></i> Konfirmasi akan dikirim ke {{ $booking->user->email }}</p>
                    <p><i class="fas fa-phone"></i> Hubungi +62 813-9264-0030 jika ada kendala</p>
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
        // Payment method selection
        document.addEventListener('DOMContentLoaded', function() {
            const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
            
            paymentMethods.forEach(method => {
                method.addEventListener('change', function() {
                    // Remove selected class from all cards
                    document.querySelectorAll('.payment-method-card').forEach(card => {
                        card.classList.remove('selected');
                    });
                    
                    // Hide all payment details
                    document.querySelectorAll('.qr-code-container, .bank-details').forEach(detail => {
                        detail.style.display = 'none';
                    });
                    
                    // Show selected payment method details
                    const selectedCard = this.closest('.payment-method-card');
                    selectedCard.classList.add('selected');
                    
                    if (this.value === 'qris') {
                        selectedCard.querySelector('.qr-code-container').style.display = 'block';
                    } else if (this.value === 'bank_transfer') {
                        selectedCard.querySelector('.bank-details').style.display = 'block';
                    }
                });
            });
        });

        // Copy to clipboard function
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Nomor rekening berhasil disalin: ' + text);
            }).catch(function(err) {
                console.error('Error copying text: ', err);
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert('Nomor rekening berhasil disalin: ' + text);
            });
        }

        // File upload preview
        document.getElementById('payment_proof').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) { // 5MB
                    alert('Ukuran file terlalu besar. Maksimal 5MB.');
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create preview if needed
                    console.log('File selected:', file.name);
                };
                reader.readAsDataURL(file);
            }
        });

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
        <div class="alert alert-success" style="position: fixed; top: 20px; right: 20px; background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 1000;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-error" style="position: fixed; top: 20px; right: 20px; background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 1000;">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
        </div>
    @endif
</body>
</html>
