<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Cancelled - Bayang Brothers</title>
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
            <div class="cancel-page">
                <div class="cancel-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                
                <h1 class="cancel-title">Payment Cancelled</h1>
                <p class="cancel-subtitle">Your booking process has been cancelled</p>

                <div class="cancel-info">
                    <div class="info-card">
                        <h3>What happened?</h3>
                        <p>You chose to cancel the payment process. No charges have been made and your booking has not been confirmed.</p>
                    </div>

                    <div class="info-card">
                        <h3>What can you do next?</h3>
                        <ul>
                            <li>Go back to the rooms page to select another room</li>
                            <li>Try booking the same room again</li>
                            <li>Contact us if you need assistance</li>
                        </ul>
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="{{ route('room.index') }}" class="btn btn-primary">
                        <i class="fas fa-bed"></i>
                        Browse Rooms
                    </a>
                    <a href="https://wa.me/6281392640030" class="btn btn-whatsapp" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                        Contact Support
                    </a>
                    <a href="{{ route('homepage') }}" class="btn btn-secondary">
                        <i class="fas fa-home"></i>
                        Back to Home
                    </a>
                </div>

                <div class="help-section">
                    <h3>Need Help?</h3>
                    <p>If you're experiencing issues with the booking process, don't hesitate to contact us:</p>
                    <div class="contact-options">
                        <a href="tel:+6281392640030" class="contact-option">
                            <i class="fas fa-phone"></i>
                            <span>+62 813-9264-0030</span>
                        </a>
                        <a href="https://wa.me/6281392640030" class="contact-option">
                            <i class="fab fa-whatsapp"></i>
                            <span>WhatsApp Chat</span>
                        </a>
                        <a href="https://instagram.com/bayangbrothers" class="contact-option">
                            <i class="fab fa-instagram"></i>
                            <span>@bayangbrothers</span>
                        </a>
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

    <style>
        .cancel-page {
            max-width: 700px;
            margin: 2rem auto;
            text-align: center;
        }

        .cancel-icon {
            font-size: 4rem;
            color: #e74c3c;
            margin-bottom: 1rem;
        }

        .cancel-title {
            color: #2c3e50;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .cancel-subtitle {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .cancel-info {
            margin-bottom: 2rem;
        }

        .info-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .info-card h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
            text-align: center;
        }

        .info-card p {
            color: #666;
            line-height: 1.6;
        }

        .info-card ul {
            color: #666;
            line-height: 1.8;
            padding-left: 1.5rem;
        }

        .info-card li {
            margin-bottom: 0.5rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .btn-whatsapp {
            background-color: #25d366;
            color: white;
        }

        .btn-whatsapp:hover {
            background-color: #128c7e;
        }

        .help-section {
            background: #f8f9fa;
            border: 1px solid #e1e8ed;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
        }

        .help-section h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .help-section p {
            color: #666;
            margin-bottom: 1.5rem;
        }

        .contact-options {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .contact-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: white;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            text-decoration: none;
            color: #2c3e50;
            transition: all 0.3s ease;
        }

        .contact-option:hover {
            border-color: #3498db;
            background-color: #f8f9fa;
            transform: translateY(-2px);
        }

        .contact-option i {
            font-size: 1.2rem;
            color: #3498db;
        }

        @media (max-width: 768px) {
            .cancel-page {
                margin: 1rem;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .contact-options {
                flex-direction: column;
                align-items: center;
            }

            .contact-option {
                width: 100%;
                max-width: 250px;
                justify-content: center;
            }
        }
    </style>
</body>
</html>
