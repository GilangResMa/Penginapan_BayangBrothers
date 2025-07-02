<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>About</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/about.css'])
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
                <a href="{{ route('about') }}" class="nav-link active">
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

    <main class="main-content">
        <div class="container">
            <div class="about-section">
                <!-- Description -->
                <div class="description">
                    <p>
                        Bayang Brothers adalah penginapan modern di jantungan Jogja yang memadukan kenyamanan kekinian
                        dengan pesona budaya lokal. Kami hadir untuk memberikan pengalaman menginap yang istimewa dan
                        suasana cozy dengan keramahan khas Jogja yang bikin betah. Setiap sudut dirancang untuk
                        kenyamananmu, dari kamar yang stylish hingga fasilitas yang bikin liburanmu makin asyik.
                        Terinspirasi dari semangat kebersamaan, Bayang Brothers ingin jadi “rumah” sementaramu saat
                        menjelajahi keindahan dan keunikan Kota Gudeg. Yuk, jadilah bagian dari cerita kami!</p>
                </div>

                <!-- Map and Contact Section -->
                <div class="map-contact-section">
                    <!-- Map -->
                    <div class="map-container">
                        <div id="map">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.2056919565644!2d110.40104887420637!3d-7.7679995770493475!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59de66ac8305%3A0xdc5875d072500293!2sOYO%20347%20Bayang%20Brothers%20Guest%20House!5e0!3m2!1sen!2sid!4v1751459420743!5m2!1sen!2sid"
                                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="contact-info">
                        <h3 class="contact-title">Contact :</h3>

                        <div class="contact-list">
                            <div class="contact-item">
                                <div class="contact-item-flex">
                                    <i class="fab fa-whatsapp contact-icon whatsapp"></i>
                                    <div>
                                        <div class="contact-label"></div>
                                        <a href="https://wa.me/6281392640030" target="_blank" class="contact-link">
                                            +6281392640030
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="contact-item">
                                <div class="contact-item-flex">
                                    <i class="fab fa-instagram contact-icon instagram"></i>
                                    <div>
                                        <div class="contact-label"></div>
                                        <a href="https://instagram.com/bayangbrothers" target="_blank"
                                            class="contact-link">
                                            bayangbrothers
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="contact-item">
                                <div class="contact-item-flex">
                                    <i class="fas fa-phone contact-icon phone"></i>
                                    <div>
                                        <div class="contact-label">Phone :</div>
                                        <a href="tel:+6281392640030" class="contact-link">
                                            +6281392640030
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="contact-item">
                                <div class="contact-item-flex items-start">
                                    <i class="fas fa-map-marker-alt contact-icon location"></i>
                                    <div>
                                        <div class="contact-label">Address :</div>
                                        <div class="contact-address">
                                            Jl. Jodipati No.15A, Dabag, Condongcatur<br>
                                            Kec. Depok, Yogyakarta<br>
                                            Daerah Istimewa Yogyakarta 55281
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Action Buttons -->
                        <div class="action-buttons">
                            <a href="https://wa.me/6281392640030" target="_blank" class="action-btn whatsapp">
                                <i class="fab fa-whatsapp"></i>
                                <span>WhatsApp</span>
                            </a>

                            <a href="https://instagram.com/bayangbrothers" target="_blank" class="action-btn instagram">
                                <i class="fab fa-instagram"></i>
                                <span>Instagram</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
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
    </footer>
</body>

</html>
