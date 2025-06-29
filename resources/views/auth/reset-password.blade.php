<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Bayang Brothers</title>
    @vite(['resources/css/login.css'])
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
                <a href="{{ route('login') }}" class="nav-login-btn">Login</a>
            </div>
        </div>
    </nav>

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
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="form-input @error('password') error @enderror"
                                       placeholder="Masukkan password baru"
                                       required>
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
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       class="form-input @error('password_confirmation') error @enderror"
                                       placeholder="Konfirmasi password baru"
                                       required>
                                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
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

    <style>
        .password-input-container {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            padding: 5px;
        }
        
        .password-toggle:hover {
            color: #667eea;
        }
        
        .password-requirements {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
        }
        
        .password-requirements h4 {
            margin: 0 0 0.5rem 0;
            color: #333;
            font-size: 0.9rem;
        }
        
        .password-requirements ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .password-requirements li {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            margin: 0.25rem 0;
            color: #666;
        }
        
        .password-requirements li.valid {
            color: #27ae60;
        }
        
        .password-requirements li.invalid {
            color: #e74c3c;
        }
        
        .auth-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: #ccc;
        }
        
        .auth-button.enabled {
            opacity: 1;
            cursor: pointer;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</body>

</html>
