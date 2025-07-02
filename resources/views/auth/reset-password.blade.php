<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password</title>
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
                        <h1>Reset Password</h1>
                        <p>Masukkan password baru untuk akun <strong>{{ $email }}</strong></p>
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

                    <form method="POST" action="{{ route('password.update') }}" class="auth-form">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">

                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock"></i>
                                Password Baru
                            </label>
                            <div class="password-input-container">
                                <input type="password" id="password" name="password"
                                    class="form-input @error('password') error @enderror"
                                    placeholder="Masukkan password baru" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="password-icon"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-lock"></i>
                                Konfirmasi Password
                            </label>
                            <div class="password-input-container">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-input @error('password_confirmation') error @enderror"
                                    placeholder="Konfirmasi password baru" required>
                                <button type="button" class="password-toggle"
                                    onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye" id="password_confirmation-icon"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password Requirements -->
                        <div class="password-requirements">
                            <h4>Requirements:</h4>
                            <ul>
                                <li id="length-req"><i class="fas fa-times"></i> Minimal 8 karakter</li>
                                <li id="letter-req"><i class="fas fa-times"></i> Mengandung huruf</li>
                                <li id="number-req"><i class="fas fa-times"></i> Mengandung angka</li>
                                <li id="match-req"><i class="fas fa-times"></i> Password cocok</li>
                            </ul>
                        </div>

                        <button type="submit" class="auth-button" id="submit-btn" disabled>
                            <i class="fas fa-save"></i>
                            Reset Password
                        </button>
                    </form>

                    <div class="auth-footer">
                        <p>Ingat password? <a href="{{ route('login') }}">Login di sini</a></p>
                    </div>
                </div>

                <!-- Security Info -->
                <div class="info-card">
                    <div class="info-header">
                        <i class="fas fa-shield-alt"></i>
                        <h3>Keamanan Password</h3>
                    </div>
                    <div class="info-content">
                        <ul>
                            <li><i class="fas fa-check"></i> Gunakan kombinasi huruf besar dan kecil</li>
                            <li><i class="fas fa-check"></i> Sertakan angka dan simbol</li>
                            <li><i class="fas fa-check"></i> Hindari informasi pribadi</li>
                            <li><i class="fas fa-check"></i> Jangan gunakan password yang sama</li>
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

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-icon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Password validation
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            const submitBtn = document.getElementById('submit-btn');

            function validatePassword() {
                const password = passwordInput.value;
                const confirm = confirmInput.value;

                // Check length
                const lengthValid = password.length >= 8;
                updateRequirement('length-req', lengthValid);

                // Check letter
                const letterValid = /[a-zA-Z]/.test(password);
                updateRequirement('letter-req', letterValid);

                // Check number
                const numberValid = /\d/.test(password);
                updateRequirement('number-req', numberValid);

                // Check match
                const matchValid = password === confirm && password.length > 0;
                updateRequirement('match-req', matchValid);

                // Enable submit button if all valid
                const allValid = lengthValid && letterValid && numberValid && matchValid;
                submitBtn.disabled = !allValid;

                if (allValid) {
                    submitBtn.classList.add('enabled');
                } else {
                    submitBtn.classList.remove('enabled');
                }
            }

            function updateRequirement(id, isValid) {
                const element = document.getElementById(id);
                const icon = element.querySelector('i');

                if (isValid) {
                    element.classList.add('valid');
                    element.classList.remove('invalid');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-check');
                } else {
                    element.classList.add('invalid');
                    element.classList.remove('valid');
                    icon.classList.remove('fa-check');
                    icon.classList.add('fa-times');
                }
            }

            passwordInput.addEventListener('input', validatePassword);
            confirmInput.addEventListener('input', validatePassword);
        });
    </script>

    <script>
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(fieldId + '-icon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Password strength validation
        document.addEventListener('DOMContentLoaded', function() {
            const passwordField = document.getElementById('password');
            const confirmField = document.getElementById('password_confirmation');
            const submitButton = document.querySelector('.auth-button');

            function validatePassword() {
                const password = passwordField.value;
                const confirm = confirmField.value;

                // Basic validation
                const isLongEnough = password.length >= 8;
                const hasMatch = password === confirm && confirm.length > 0;

                // Update button state
                if (isLongEnough && hasMatch) {
                    submitButton.disabled = false;
                    submitButton.classList.add('enabled');
                } else {
                    submitButton.disabled = true;
                    submitButton.classList.remove('enabled');
                }
            }

            passwordField.addEventListener('input', validatePassword);
            confirmField.addEventListener('input', validatePassword);

            // Initial validation
            validatePassword();
        });
    </script>
</body>

</html>
