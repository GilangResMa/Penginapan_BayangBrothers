<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot Password - Bayang Brothers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/auth.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
</head>

<body>
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
                <a href="{{ route('login') }}" class="login-button">
                    <i class="fas fa-sign-in-alt"></i>
                    Login
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main">
        <div class="container">
            <div class="auth-container">
                <div class="auth-card">
                    <div class="auth-header">
                        <h1>Forgot Password</h1>
                        <p>Masukkan email Anda untuk mendapatkan link reset password</p>
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

                    @if ($errors->any())
                        <div class="alert alert-error">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" class="auth-form">
                        @csrf

                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i>
                                Email Address
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="form-input @error('email') error @enderror"
                                   value="{{ old('email') }}" 
                                   placeholder="Masukkan email Anda"
                                   required>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="auth-button">
                            <i class="fas fa-paper-plane"></i>
                            Send Reset Link
                        </button>
                    </form>

                    <div class="auth-footer">
                        <p>Sudah ingat password? <a href="{{ route('login') }}">Login di sini</a></p>
                        <p>Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="info-card">
                    <div class="info-header">
                        <i class="fas fa-info-circle"></i>
                        <h3>Informasi Reset Password</h3>
                    </div>
                    <div class="info-content">
                        <ul>
                            <li><i class="fas fa-check"></i> Link reset akan dikirim ke email Anda</li>
                            <li><i class="fas fa-check"></i> Link berlaku selama 60 menit</li>
                            <li><i class="fas fa-check"></i> Password baru minimal 8 karakter</li>
                            <li><i class="fas fa-check"></i> Gunakan kombinasi huruf, angka, dan simbol</li>
                        </ul>
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
    </footer> 
</body>

</html>
