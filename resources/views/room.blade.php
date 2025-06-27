<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Room</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/room.css'])
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
                <a href="/" class="nav-link">
                    <i class="fas fa-home"></i>
                    <span>Home</span>
                </a>
                <a href="#" class="nav-link active">
                    <i class="fas fa-bed"></i>
                    <span>Room</span>
                </a>
                <a href="#" class="nav-link">
                    <i class="fas fa-info-circle"></i>
                    <span>About</span>
                </a>
                <a href="#" class="nav-link">
                    <i class="fas fa-question-circle"></i>
                    <span>FAQ</span>
                </a>
                <a href="login" class="login-button">
                    Login
                </a>
            </nav>
        </div>
    </header>
    <main class="main-content">
        <div class="container">
            <div class="room-detail">
                <div class="room-grid">
                    <!-- Image Gallery -->
                    <div class="image-gallery">
                        <div class="main-image">
                            <img src="{{ asset('img/kamar1.jpg') }}" alt="Kamar">
                        </div>
                        <div class="thumbnail-grid">
                            <img src="{{ asset('img/kamar2.jpg') }}" alt="Kamar 2">
                            <img src="{{ asset('img/meja.jpg') }}" alt="Meja">
                            <img src="{{ asset('img/toilet.jpg') }}" alt="Toilet">
                            <img src="{{ asset('img/wc.jpg') }}" alt="WC">
                        </div>
                    </div>

                    <!-- Room Info -->
                    <div class="room-info">
                        <h2 class="facilities-title">Fasilitas Kamar :</h2>

                        <div class="facilities">
                            <div class="facility-column">
                                <ul class="facility-list">
                                    <li class="facility-item">
                                        <span class="facility-bullet"></span>
                                        Queen Bed
                                    </li>
                                    <li class="facility-item">
                                        <span class="facility-bullet"></span>
                                        TV
                                    </li>
                                    <li class="facility-item">
                                        <span class="facility-bullet"></span>
                                        AC
                                    </li>
                                    <li class="facility-item">
                                        <span class="facility-bullet"></span>
                                        Kamar Mandi Dalam
                                    </li>
                                </ul>
                            </div>
                            <div class="facility-column">
                                <ul class="facility-list">
                                    <li class="facility-item">
                                        <span class="facility-bullet"></span>
                                        Lemari
                                    </li>
                                    <li class="facility-item">
                                        <span class="facility-bullet"></span>
                                        Meja Rias
                                    </li>
                                    <li class="facility-item">
                                        <span class="facility-bullet"></span>
                                        Air Panas
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="description">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt
                                ut
                                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                                ullamco
                                laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit
                                in
                                voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat
                                cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                            </p>
                        </div>

                        <div class="price">
                            <div class="price-main">
                                <span class="price-label">Price: </span>
                                <span id="total-price" class="price-amount">Rp 0</span>
                            </div>
                            <div id="price-details" style="display: none;" class="price-details">
                                <small id="nights-info"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Section -->
            <div class="booking-section">
                <div class="booking-inputs">
                    <div class="input-group">
                        <label class="input-label">
                            <i class="fas fa-calendar"></i>
                            Check In - Check Out
                        </label>
                        <div class="date-inputs">
                            <input type="date" id="checkin" class="date-input">
                            <span class="date-separator">-</span>
                            <input type="date" id="checkout" class="date-input">
                        </div>
                    </div>

                    <div class="input-group">
                        <label class="input-label">
                            <i class="fas fa-user"></i>
                            Person
                        </label>
                        <select id="persons" class="select-input">
                            <option value="1">1 Person</option>
                            <option value="2">2 Persons</option>
                            <option value="3">3 Persons</option>
                            <option value="4">4 Persons</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label class="input-label">Contact Admin</label>
                        <button class="admin-button">
                            <i class="fab fa-whatsapp"></i>
                            Cari Admin
                        </button>
                    </div>

                    <div class="input-group">
                        <a href="/payment" class="booking-btn" onclick="calculatePrice()">
                            Booking Now
                        </a>
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
