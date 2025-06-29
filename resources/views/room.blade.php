<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room - Bayang Brothers</title>
    @vite(['resources/css/room.css', 'resources/js/room.js'])
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
                <a href="{{ route('room.index') }}" class="nav-link active">
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

            @forelse ($rooms as $room)
                <div class="room-detail">
                    <div class="room-grid">
                        <!-- Image Gallery -->
                        <div class="image-gallery">
                            <div class="main-image">
                                <img src="{{ asset('img/kamar1.jpg') }}" alt="{{ $room->name }}">
                            </div>
                            <div class="thumbnail-grid">
                                <img src="{{ asset('img/kamar2.jpg') }}" alt="Kamar 2">
                                <img src="{{ asset('img/meja.jpg') }}" alt="Meja">
                                <img src="{{ asset('img/toilet.jpg') }}" alt="Toilet">
                                <img src="{{ asset('img/km.jpg') }}" alt="WC">
                            </div>
                        </div>

                        <!-- Room Info -->
                        <div class="room-info">
                            <h2 class="room-title">{{ $room->name }}</h2>
                            <p class="room-type">Max {{ $room->max_guests }} guests</p>

                            <h3 class="facilities-title">Fasilitas Kamar:</h3>
                            <div class="facilities">
                                <div class="facility-column">
                                    <ul class="facility-list">
                                        <li class="facility-item">
                                            <span class="facility-bullet"></span>
                                            AC
                                        </li>
                                        <li class="facility-item">
                                            <span class="facility-bullet"></span>
                                            TV
                                        </li>
                                        <li class="facility-item">
                                            <span class="facility-bullet"></span>
                                            Wi-Fi
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
                                <p>{{ $room->description ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' }}
                                </p>
                            </div>

                            <div class="price">
                                <div class="price-main">
                                    <span class="price-label">Price: </span>
                                    <span id="total-price-{{ $room->id }}" class="price-amount">Rp
                                        {{ number_format($room->price_weekday, 0, ',', '.') }}</span>
                                </div>
                                <div id="price-details-{{ $room->id }}" style="display: none;"
                                    class="price-details">
                                    <small id="nights-info-{{ $room->id }}"></small>
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
                                    <input type="date" id="checkin_{{ $room->id }}" class="date-input"
                                        onchange="updatePriceDisplayForRoom({{ $room->id }})">
                                    <span class="date-separator">-</span>
                                    <input type="date" id="checkout_{{ $room->id }}" class="date-input"
                                        onchange="updatePriceDisplayForRoom({{ $room->id }})">
                                </div>
                            </div>

                            <div class="input-group">
                                <label class="input-label">
                                    <i class="fas fa-user"></i>
                                    Person
                                </label>
                                <select id="persons_{{ $room->id }}" class="select-input"
                                    onchange="updatePriceDisplayForRoom({{ $room->id }})">
                                    <option value="1">1 Person</option>
                                    <option value="2">2 Persons</option>
                                    <option value="3">3 Persons</option>
                                    <option value="4">4 Persons</option>
                                    <option value="5">5 Persons</option>
                                    <option value="6">6 Persons</option>
                                </select>
                            </div>

                            <div class="input-group">
                                <label class="input-label">Contact Admin</label>
                                <button class="admin-button"
                                    onclick="window.open('https://wa.me/6281392640030', '_blank')">
                                    <i class="fab fa-whatsapp"></i>
                                    Cari Admin
                                </button>
                            </div>

                            <div class="input-group">
                                @auth('web')
                                    <!-- User sudah login, bisa booking -->
                                    <form method="POST" action="{{ route('room.book', $room->id) }}">
                                        @csrf
                                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                                        <input type="hidden" name="check_in" id="hidden_checkin_{{ $room->id }}">
                                        <input type="hidden" name="check_out" id="hidden_checkout_{{ $room->id }}">
                                        <input type="hidden" name="guests" id="hidden_persons_{{ $room->id }}">
                                        <input type="hidden" name="extra_bed"
                                            id="hidden_extra_bed_{{ $room->id }}">
                                        <input type="hidden" name="total_cost"
                                            id="hidden_total_cost_{{ $room->id }}">
                                        <button type="submit" class="booking-btn"
                                            onclick="return setBookingData({{ $room->id }})">
                                            <i class="fas fa-calendar-check"></i>
                                            Booking Now
                                        </button>
                                    </form>
                                @else
                                    <!-- User belum login, arahkan ke login -->
                                    <a href="{{ route('login') }}" class="booking-btn login-required">
                                        <i class="fas fa-sign-in-alt"></i>
                                        Login to Book
                                    </a>
                                    <p class="login-notice">Silakan login terlebih dahulu untuk melakukan booking</p>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>

                @if (!$loop->last)
                    <hr class="room-separator">
                @endif

            @empty
                <div class="empty-rooms">
                    <h3>No Rooms Available</h3>
                    <p>Currently there are no rooms available for booking.</p>
                    <p>Please check back later or <a href="tel:+6281392640030" class="contact-admin">contact us</a>
                        for more information.</p>
                </div>
            @endforelse
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

    <script>
        // Initialize room booking for multiple rooms
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($rooms as $room)
                initializeRoomBookingForRoom({{ $room->id }}, {{ $room->price_weekday }});
            @endforeach
        });

        // Initialize for specific room
        function initializeRoomBookingForRoom(roomId, basePrice) {
            const today = new Date().toISOString().split('T')[0];
            const checkinInput = document.getElementById(`checkin_${roomId}`);
            const checkoutInput = document.getElementById(`checkout_${roomId}`);

            if (checkinInput && checkoutInput) {
                checkinInput.setAttribute('min', today);
                checkoutInput.setAttribute('min', today);

                checkinInput.addEventListener('change', function() {
                    const checkinDate = new Date(this.value);
                    checkinDate.setDate(checkinDate.getDate() + 1);
                    const minCheckout = checkinDate.toISOString().split('T')[0];
                    checkoutInput.setAttribute('min', minCheckout);
                });
            }
        }

        // Update price display for specific room
        function updatePriceDisplayForRoom(roomId) {
            const checkinValue = document.getElementById(`checkin_${roomId}`)?.value;
            const checkoutValue = document.getElementById(`checkout_${roomId}`)?.value;
            const guestsValue = document.getElementById(`persons_${roomId}`)?.value;

            if (!checkinValue || !checkoutValue) {
                return;
            }

            const result = calculateBookingCost(checkinValue, checkoutValue, guestsValue);

            if (result.totalNights === 0) {
                return;
            }

            // Update total harga untuk room ini
            const totalPriceElement = document.getElementById(`total-price-${roomId}`);
            if (totalPriceElement) {
                totalPriceElement.textContent = formatRupiah(result.totalCost);
            }

            // Update detail breakdown untuk room ini
            let detailText = `${result.totalNights} malam untuk ${result.guests} orang`;

            if (result.weekendNights > 0 && result.weekdayNights > 0) {
                detailText +=
                    `\n• Kamar: ${result.weekdayNights} hari biasa × ${formatRupiah(150000)} + ${result.weekendNights} weekend × ${formatRupiah(180000)}`;
            } else if (result.weekendNights > 0) {
                detailText += `\n• Kamar: ${result.weekendNights} malam weekend × ${formatRupiah(180000)}`;
            } else {
                detailText += `\n• Kamar: ${result.weekdayNights} malam hari biasa × ${formatRupiah(150000)}`;
            }

            if (result.needsExtraBed) {
                detailText += `\n• Extra Bed: ${result.totalNights} malam × ${formatRupiah(70000)}`;
            }

            const nightsInfoElement = document.getElementById(`nights-info-${roomId}`);
            const priceDetailsElement = document.getElementById(`price-details-${roomId}`);

            if (nightsInfoElement && priceDetailsElement) {
                nightsInfoElement.innerHTML = detailText.replace(/\n/g, '<br>');
                priceDetailsElement.style.display = 'block';
            }
        }
    </script>
</body>

</html>
