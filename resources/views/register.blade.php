<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/register.css'])
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
                <a href="{{ route('homepage') }}" class="nav-link active">
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
    <main class="register-main">
        <div class="register-container">
            <h2 class="register-title">Sign Up</h2>

            <form class="register-form" method="POST" action="{{ route('register.store') }}">
                @csrf
                
                @if($errors->any())
                    <div class="error-container">
                        @foreach($errors->all() as $error)
                            <p class="error-message">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <!-- Name Field -->
                <div>
                    <input name="name" type="text" placeholder="Name" class="input-field @error('name') error @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Kontak Field -->
                <div>
                    <input name="contact" type="text" placeholder="Nomor Telepon" class="input-field @error('contact') error @enderror" value="{{ old('contact') }}" required>
                    @error('contact')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Email Field -->
                <div>
                    <input name="email" type="email" placeholder="Email" class="input-field @error('email') error @enderror" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Password Field -->
                <div>
                    <div class="password-field-container">
                        <input name="password" type="password" placeholder="Password" class="input-field password-input @error('password') error @enderror" id="password" required>
                        <button type="button" class="password-toggle" onclick="togglePassword(event)">
                            <i class="fas fa-eye" id="password-toggle-icon"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Sign Up Button -->
                <button type="submit" class="signup-button">
                    Sign Up
                </button>
                
                <!-- Sign In Link -->
                <p class="signin-text">
                    Already Have an Account?
                    <a href="login" class="signin-link">Sign In</a>
                </p>
            </form>
        </div>
    </main>
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
        function togglePassword(event) {
            // Prevent form submission
            if (event) {
                event.preventDefault();
            }
            
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>
