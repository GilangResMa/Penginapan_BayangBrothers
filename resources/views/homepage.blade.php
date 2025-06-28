<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Homepage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/homepage.css'])
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
    <main class="main-container">
        <div class="content-grid">

            <!-- Left Content -->
            <div class="content-card">
                <p class="content-text">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                    aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
                    culpa qui officia deserunt mollit anim id est laborum.
                </p>

                <!-- Go to Room Button -->
                <div class="button-container">
                    <a href="room" class="go-to-room-button">
                        Go to Room
                    </a>
                </div>
            </div>

            <!-- Right Content - Images -->
            <div class="image-container">
                <div class="image-wrapper">
                    <img src="{{ asset('img/home.jpg') }}" alt="Home" class="home-image">
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
