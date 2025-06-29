<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Room - Bayang Brothers</title>
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
            <!-- Search Availability Form -->
            <div class="search-availability">
                <h2 class="search-title">Cek Ketersediaan Kamar</h2>
                <form method="GET" action="{{ route('room.index') }}" class="search-form">
                    <div class="search-inputs">
                        <div class="search-input-group">
                            <label for="search_check_in">Check In</label>
                            <input type="date" 
                                   id="search_check_in" 
                                   name="check_in" 
                                   value="{{ $checkIn ?? '' }}"
                                   min="{{ date('Y-m-d') }}"
                                   class="search-date-input">
                        </div>
                        <div class="search-input-group">
                            <label for="search_check_out">Check Out</label>
                            <input type="date" 
                                   id="search_check_out" 
                                   name="check_out" 
                                   value="{{ $checkOut ?? '' }}"
                                   min="{{ date('Y-m-d') }}"
                                   class="search-date-input">
                        </div>
                        <div class="search-input-group">
                            <button type="submit" class="search-btn">
                                <i class="fas fa-search"></i>
                                Cek Ketersediaan
                            </button>
                        </div>
                    </div>
                </form>
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

            @forelse ($rooms as $room)
                <div class="room-detail" data-room-id="{{ $room->id }}" 
                     data-price-weekday="{{ $room->price_weekday ?? 150000 }}"
                     data-price-weekend="{{ $room->price_weekend ?? 180000 }}"
                     data-extra-bed-price="{{ $room->extra_bed_price ?? 70000 }}"
                     data-total-quantity="{{ $room->total_quantity ?? 1 }}">
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
                            
                            <!-- Availability Info -->
                            <div class="availability-info">
                                @if(isset($room->available_for_dates))
                                    @if($room->is_available)
                                        <div class="availability available">
                                            <i class="fas fa-check-circle"></i>
                                            <span>{{ $room->available_for_dates }} dari {{ $room->total_quantity }} kamar tersedia</span>
                                        </div>
                                    @else
                                        <div class="availability unavailable">
                                            <i class="fas fa-times-circle"></i>
                                            <span>Tidak ada kamar tersedia untuk tanggal yang dipilih</span>
                                        </div>
                                    @endif
                                @else
                                    <div class="availability default">
                                        <i class="fas fa-info-circle"></i>
                                        <span>{{ $room->total_quantity }} kamar tersedia</span>
                                    </div>
                                @endif
                            </div>

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
                                    @if(!isset($room->is_available) || $room->is_available)
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
                                        <button class="booking-btn disabled" disabled>
                                            <i class="fas fa-ban"></i>
                                            Tidak Tersedia
                                        </button>
                                        <p class="unavailable-notice">Kamar tidak tersedia untuk tanggal yang dipilih</p>
                                    @endif
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
        </div>
    </footer>

    <script>
        // Global variables for room management
        let roomsData = [];
        let roomPrices = {}; // Store room prices dynamically

        // Default fallback prices (for backward compatibility)
        const WEEKDAY_PRICE = 150000;
        const WEEKEND_PRICE = 180000;
        const EXTRA_BED_PRICE = 70000;

        // Function untuk mengecek apakah tanggal adalah weekend
        function isWeekend(date) {
            const dayOfWeek = date.getDay();
            return dayOfWeek === 0 || dayOfWeek === 6; // 0 = Minggu, 6 = Sabtu
        }

        // Function untuk menghitung total biaya booking
        function calculateBookingCost(
            checkinDate,
            checkoutDate,
            guests = 1,
            roomId = null
        ) {
            if (!checkinDate || !checkoutDate) return 0;

            const checkin = new Date(checkinDate);
            const checkout = new Date(checkoutDate);

            // Validasi tanggal
            if (checkout <= checkin) return 0;

            // Get room specific prices or use defaults
            let weekdayPrice = WEEKDAY_PRICE;
            let weekendPrice = WEEKEND_PRICE;
            let extraBedPrice = EXTRA_BED_PRICE;

            if (roomId && roomPrices[roomId]) {
                weekdayPrice = roomPrices[roomId].weekday;
                weekendPrice = roomPrices[roomId].weekend;
                extraBedPrice = roomPrices[roomId].extraBed;
            }

            let totalCost = 0;
            let weekdayNights = 0;
            let weekendNights = 0;
            let extraBedCost = 0;

            // Loop untuk setiap malam menginap
            const currentDate = new Date(checkin);
            while (currentDate < checkout) {
                if (isWeekend(currentDate)) {
                    weekendNights++;
                    totalCost += weekendPrice;
                } else {
                    weekdayNights++;
                    totalCost += weekdayPrice;
                }
                currentDate.setDate(currentDate.getDate() + 1);
            }

            // Hitung biaya extra bed jika guests > 2
            const totalNights = weekdayNights + weekendNights;
            const needsExtraBed = parseInt(guests) > 2;
            if (needsExtraBed) {
                extraBedCost = extraBedPrice * totalNights;
                totalCost += extraBedCost;
            }

            return {
                totalCost: totalCost,
                totalNights: totalNights,
                weekdayNights: weekdayNights,
                weekendNights: weekendNights,
                extraBedCost: extraBedCost,
                needsExtraBed: needsExtraBed,
                guests: parseInt(guests),
                weekdayPrice: weekdayPrice,
                weekendPrice: weekendPrice,
                extraBedPrice: extraBedPrice,
            };
        }

        // Function untuk format rupiah
        function formatRupiah(amount) {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0,
            }).format(amount);
        }

        // Function untuk update tampilan harga (legacy - untuk backward compatibility)
        function updatePriceDisplay() {
            const checkinValue = document.getElementById("checkin")?.value;
            const checkoutValue = document.getElementById("checkout")?.value;
            const guestsValue = document.getElementById("persons")?.value;

            if (!checkinValue || !checkoutValue) {
                // Reset ke harga default jika tanggal belum dipilih
                const totalPriceElement = document.getElementById("total-price");
                if (totalPriceElement) {
                    totalPriceElement.textContent = formatRupiah(WEEKDAY_PRICE);
                }
                const priceDetailsElement = document.getElementById("price-details");
                if (priceDetailsElement) {
                    priceDetailsElement.style.display = "none";
                }
                return;
            }

            const result = calculateBookingCost(
                checkinValue,
                checkoutValue,
                guestsValue
            );

            if (result.totalNights === 0) {
                const totalPriceElement = document.getElementById("total-price");
                if (totalPriceElement) {
                    totalPriceElement.textContent = formatRupiah(WEEKDAY_PRICE);
                }
                const priceDetailsElement = document.getElementById("price-details");
                if (priceDetailsElement) {
                    priceDetailsElement.style.display = "none";
                }
                return;
            }

            // Update total harga
            const totalPriceElement = document.getElementById("total-price");
            if (totalPriceElement) {
                totalPriceElement.textContent = formatRupiah(result.totalCost);
            }

            // Update detail breakdown using dynamic pricing
            let detailText = `${result.totalNights} malam untuk ${result.guests} orang`;

            // Detail harga kamar
            if (result.weekendNights > 0 && result.weekdayNights > 0) {
                detailText += `\n• Kamar: ${
                    result.weekdayNights
                } hari biasa × ${formatRupiah(result.weekdayPrice)} + ${
                    result.weekendNights
                } weekend × ${formatRupiah(result.weekendPrice)}`;
            } else if (result.weekendNights > 0) {
                detailText += `\n• Kamar: ${
                    result.weekendNights
                } malam weekend × ${formatRupiah(result.weekendPrice)}`;
            } else {
                detailText += `\n• Kamar: ${
                    result.weekdayNights
                } malam hari biasa × ${formatRupiah(result.weekdayPrice)}`;
            }

            // Detail extra bed jika ada
            if (result.needsExtraBed) {
                detailText += `\n• Extra Bed: ${
                    result.totalNights
                } malam × ${formatRupiah(result.extraBedPrice)}`;
            }

            const nightsInfoElement = document.getElementById("nights-info");
            const priceDetailsElement = document.getElementById("price-details");

            if (nightsInfoElement && priceDetailsElement) {
                nightsInfoElement.innerHTML = detailText.replace(/\n/g, "<br>");
                priceDetailsElement.style.display = "block";
            }
        }

        // Set booking data dari form ke hidden inputs
        function setBookingData(roomId = null) {
            // Jika tidak ada roomId, gunakan ID default (untuk backward compatibility)
            const checkinId = roomId ? `checkin_${roomId}` : "checkin";
            const checkoutId = roomId ? `checkout_${roomId}` : "checkout";
            const personsId = roomId ? `persons_${roomId}` : "persons";

            const checkin =
                document.getElementById(checkinId)?.value ||
                document.getElementById("checkin")?.value;
            const checkout =
                document.getElementById(checkoutId)?.value ||
                document.getElementById("checkout")?.value;
            const persons =
                document.getElementById(personsId)?.value ||
                document.getElementById("persons")?.value;

            // Validasi input
            if (!checkin || !checkout) {
                alert("Silakan pilih tanggal check-in dan check-out");
                return false;
            }

            if (new Date(checkin) >= new Date(checkout)) {
                alert("Tanggal check-out harus setelah check-in");
                return false;
            }

            // Validasi minimal 1 malam - pass roomId for dynamic pricing
            const result = calculateBookingCost(checkin, checkout, persons, roomId);
            if (result.totalNights === 0) {
                alert("Minimal booking 1 malam");
                return false;
            }

            // Konfirmasi jika ada extra bed - use dynamic pricing
            if (result.needsExtraBed) {
                const confirmExtraBed = confirm(
                    `Untuk ${persons} orang, akan dikenakan biaya extra bed ${formatRupiah(
                        result.extraBedPrice
                    )} per malam.\n` +
                        `Total biaya extra bed: ${formatRupiah(
                            result.extraBedCost
                        )}\n\n` +
                        `Total keseluruhan: ${formatRupiah(result.totalCost)}\n\n` +
                        `Lanjutkan booking?`
                );

                if (!confirmExtraBed) {
                    return false;
                }
            }

            // Set hidden inputs berdasarkan roomId
            if (roomId) {
                document.getElementById(`hidden_checkin_${roomId}`).value = checkin;
                document.getElementById(`hidden_checkout_${roomId}`).value = checkout;
                document.getElementById(`hidden_persons_${roomId}`).value = persons;
                document.getElementById(`hidden_extra_bed_${roomId}`).value =
                    result.needsExtraBed ? "1" : "0";
                document.getElementById(`hidden_total_cost_${roomId}`).value =
                    result.totalCost;
            } else {
                // Fallback untuk kompatibilitas
                document.getElementById("hidden_checkin").value = checkin;
                document.getElementById("hidden_checkout").value = checkout;
                document.getElementById("hidden_persons").value = persons;
                document.getElementById("hidden_total_cost").value = result.totalCost;
            }

            return true;
        }

        // Initialize room booking functionality
        function initializeRoomBooking() {
            const today = new Date().toISOString().split("T")[0];
            const checkinInput = document.getElementById("checkin");
            const checkoutInput = document.getElementById("checkout");
            const personsSelect = document.getElementById("persons");

            // Only initialize if elements exist (single room page)
            if (!checkinInput || !checkoutInput || !personsSelect) {
                return;
            }

            // Set minimum date
            checkinInput.setAttribute("min", today);
            checkoutInput.setAttribute("min", today);

            // Event listener untuk perubahan tanggal check-in
            checkinInput.addEventListener("change", function () {
                const checkinDate = new Date(this.value);
                checkinDate.setDate(checkinDate.getDate() + 1);
                const minCheckout = checkinDate.toISOString().split("T")[0];
                checkoutInput.setAttribute("min", minCheckout);

                // Update harga ketika check-in berubah
                updatePriceDisplay();
            });

            // Event listener untuk perubahan tanggal check-out
            checkoutInput.addEventListener("change", function () {
                updatePriceDisplay();
            });

            // Event listener untuk perubahan jumlah orang
            personsSelect.addEventListener("change", function () {
                updatePriceDisplay();

                // Tampilkan notifikasi extra bed jika > 2 orang
                if (parseInt(this.value) > 2) {
                    showExtraBedNotification(true);
                } else {
                    showExtraBedNotification(false);
                }
            });

            // Initial price display
            updatePriceDisplay();
        }

        // Function untuk menampilkan notifikasi extra bed
        function showExtraBedNotification(show, roomId = null) {
            let notification = document.getElementById("extra-bed-notification");

            if (show) {
                // Get dynamic extra bed price
                let extraBedPrice = EXTRA_BED_PRICE;
                if (roomId && roomPrices[roomId]) {
                    extraBedPrice = roomPrices[roomId].extraBed;
                }

                if (!notification) {
                    // Buat notifikasi jika belum ada
                    notification = document.createElement("div");
                    notification.id = "extra-bed-notification";
                    notification.className = "extra-bed-notification";

                    // Insert setelah persons select
                    const personsGroup = document
                        .getElementById("persons")
                        ?.closest(".input-group");
                    if (personsGroup) {
                        personsGroup.insertAdjacentElement("afterend", notification);
                    }
                }

                if (notification) {
                    notification.innerHTML = `
                        <i class="fas fa-info-circle"></i>
                        <strong>Extra Bed diperlukan!</strong><br>
                        Biaya tambahan ${formatRupiah(
                            extraBedPrice
                        )} per malam untuk lebih dari 2 orang.
                    `;
                    notification.style.display = "block";
                }
            } else {
                if (notification) {
                    notification.style.display = "none";
                }
            }
        }

        // Initialize multiple room booking functionality
        function initializeMultipleRoomBooking(rooms) {
            console.log("Initializing multiple room booking with data:", rooms);

            roomsData = rooms;

            // Store room prices for easy access
            roomPrices = {};
            rooms.forEach((room) => {
                roomPrices[room.id] = {
                    weekday: room.price_weekday || WEEKDAY_PRICE,
                    weekend: room.price_weekend || WEEKEND_PRICE,
                    extraBed: room.extra_bed_price || EXTRA_BED_PRICE,
                };
            });

            console.log("Room prices stored:", roomPrices);

            // Initialize search form date validation
            initializeSearchForm();

            // Initialize each room booking
            rooms.forEach((room) => {
                initializeRoomBookingForRoom(room.id, room.price_weekday);
            });
        }

        // Initialize search form functionality
        function initializeSearchForm() {
            const searchCheckinInput = document.getElementById("search_check_in");
            const searchCheckoutInput = document.getElementById("search_check_out");

            if (searchCheckinInput && searchCheckoutInput) {
                searchCheckinInput.addEventListener("change", function () {
                    const checkinDate = new Date(this.value);
                    checkinDate.setDate(checkinDate.getDate() + 1);
                    const minCheckout = checkinDate.toISOString().split("T")[0];
                    searchCheckoutInput.setAttribute("min", minCheckout);

                    // Clear checkout if it's before new minimum
                    if (
                        searchCheckoutInput.value &&
                        searchCheckoutInput.value <= this.value
                    ) {
                        searchCheckoutInput.value = "";
                    }
                });
            }
        }

        // Initialize for specific room
        function initializeRoomBookingForRoom(roomId, basePrice) {
            const today = new Date().toISOString().split("T")[0];
            const checkinInput = document.getElementById(`checkin_${roomId}`);
            const checkoutInput = document.getElementById(`checkout_${roomId}`);

            if (checkinInput && checkoutInput) {
                checkinInput.setAttribute("min", today);
                checkoutInput.setAttribute("min", today);

                checkinInput.addEventListener("change", function () {
                    const checkinDate = new Date(this.value);
                    checkinDate.setDate(checkinDate.getDate() + 1);
                    const minCheckout = checkinDate.toISOString().split("T")[0];
                    checkoutInput.setAttribute("min", minCheckout);
                });
            }
        }

        // Update price display for specific room
        function updatePriceDisplayForRoom(roomId) {
            console.log("updatePriceDisplayForRoom called for roomId:", roomId);

            const checkinValue = document.getElementById(`checkin_${roomId}`)?.value;
            const checkoutValue = document.getElementById(`checkout_${roomId}`)?.value;
            const guestsValue = document.getElementById(`persons_${roomId}`)?.value;

            console.log("Input values:", { checkinValue, checkoutValue, guestsValue });

            if (!checkinValue || !checkoutValue) {
                console.log("Missing checkin or checkout values, returning early");
                return;
            }

            // Check availability via AJAX
            checkRoomAvailability(roomId, checkinValue, checkoutValue);

            // Pass roomId to calculateBookingCost for dynamic pricing
            const result = calculateBookingCost(
                checkinValue,
                checkoutValue,
                guestsValue,
                roomId
            );
            console.log("Calculation result:", result);

            if (result.totalNights === 0) {
                console.log("Total nights is 0, returning early");
                return;
            }

            // Update total harga untuk room ini
            const totalPriceElement = document.getElementById(`total-price-${roomId}`);
            if (totalPriceElement) {
                totalPriceElement.textContent = formatRupiah(result.totalCost);
                console.log(
                    "Updated price element with:",
                    formatRupiah(result.totalCost)
                );
            } else {
                console.log("Price element not found for roomId:", roomId);
            }

            // Update detail breakdown untuk room ini menggunakan harga dinamis
            let detailText = `${result.totalNights} malam untuk ${result.guests} orang`;

            if (result.weekendNights > 0 && result.weekdayNights > 0) {
                detailText += `\n• Kamar: ${
                    result.weekdayNights
                } hari biasa × ${formatRupiah(result.weekdayPrice)} + ${
                    result.weekendNights
                } weekend × ${formatRupiah(result.weekendPrice)}`;
            } else if (result.weekendNights > 0) {
                detailText += `\n• Kamar: ${
                    result.weekendNights
                } malam weekend × ${formatRupiah(result.weekendPrice)}`;
            } else {
                detailText += `\n• Kamar: ${
                    result.weekdayNights
                } malam hari biasa × ${formatRupiah(result.weekdayPrice)}`;
            }

            if (result.needsExtraBed) {
                detailText += `\n• Extra Bed: ${
                    result.totalNights
                } malam × ${formatRupiah(result.extraBedPrice)}`;
            }

            const nightsInfoElement = document.getElementById(`nights-info-${roomId}`);
            const priceDetailsElement = document.getElementById(
                `price-details-${roomId}`
            );

            if (nightsInfoElement && priceDetailsElement) {
                nightsInfoElement.innerHTML = detailText.replace(/\n/g, "<br>");
                priceDetailsElement.style.display = "block";
                console.log("Updated price details successfully");
            } else {
                console.log("Price details elements not found for roomId:", roomId);
            }
        }

        // Check room availability via AJAX
        function checkRoomAvailability(roomId, checkIn, checkOut) {
            const csrfToken =
                document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute("content") || "";

            fetch("/api/room/availability", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({
                    room_id: roomId,
                    check_in: checkIn,
                    check_out: checkOut,
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    updateAvailabilityDisplay(roomId, data);
                })
                .catch((error) => {
                    console.error("Error checking availability:", error);
                });
        }

        // Update availability display
        function updateAvailabilityDisplay(roomId, data) {
            const availabilityElement = document.querySelector(
                `[data-room-id="${roomId}"] .availability-info`
            );
            if (availabilityElement) {
                if (data.available) {
                    availabilityElement.innerHTML = `
                        <div class="availability available">
                            <i class="fas fa-check-circle"></i>
                            <span>${data.message}</span>
                        </div>
                    `;
                    // Enable booking button
                    const bookingBtn = document.querySelector(
                        `[data-room-id="${roomId}"] .booking-btn`
                    );
                    if (
                        bookingBtn &&
                        !bookingBtn.classList.contains("login-required")
                    ) {
                        bookingBtn.disabled = false;
                        bookingBtn.classList.remove("disabled");
                        bookingBtn.innerHTML =
                            '<i class="fas fa-calendar-check"></i> Booking Now';
                    }
                } else {
                    availabilityElement.innerHTML = `
                        <div class="availability unavailable">
                            <i class="fas fa-times-circle"></i>
                            <span>${data.message}</span>
                        </div>
                    `;
                    // Disable booking button
                    const bookingBtn = document.querySelector(
                        `[data-room-id="${roomId}"] .booking-btn`
                    );
                    if (
                        bookingBtn &&
                        !bookingBtn.classList.contains("login-required")
                    ) {
                        bookingBtn.disabled = true;
                        bookingBtn.classList.add("disabled");
                        bookingBtn.innerHTML =
                            '<i class="fas fa-ban"></i> Tidak Tersedia';
                    }
                }
            }
        }

        // Global functions to be called from Blade templates
        window.initializeMultipleRoomBooking = initializeMultipleRoomBooking;
        window.initializeRoomBookingForRoom = initializeRoomBookingForRoom;
        window.updatePriceDisplayForRoom = updatePriceDisplayForRoom;
        window.setBookingData = setBookingData;
        window.checkRoomAvailability = checkRoomAvailability;
        window.updateAvailabilityDisplay = updateAvailabilityDisplay;
        window.calculateBookingCost = calculateBookingCost;
        window.formatRupiah = formatRupiah;

        // Initialize room booking system when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Get rooms data from HTML data attributes
            const roomElements = document.querySelectorAll('.room-detail[data-room-id]');
            const roomsData = [];
            
            roomElements.forEach(function(roomElement) {
                const roomId = parseInt(roomElement.getAttribute('data-room-id'));
                const priceWeekday = parseFloat(roomElement.getAttribute('data-price-weekday')) || 150000;
                const priceWeekend = parseFloat(roomElement.getAttribute('data-price-weekend')) || 180000;
                const extraBedPrice = parseFloat(roomElement.getAttribute('data-extra-bed-price')) || 70000;
                const totalQuantity = parseInt(roomElement.getAttribute('data-total-quantity')) || 1;
                
                roomsData.push({
                    id: roomId,
                    price_weekday: priceWeekday,
                    price_weekend: priceWeekend,
                    extra_bed_price: extraBedPrice,
                    total_quantity: totalQuantity
                });
            });

            console.log('Rooms data extracted from DOM:', roomsData);

            // Initialize multiple room booking system
            if (typeof window.initializeMultipleRoomBooking === 'function') {
                window.initializeMultipleRoomBooking(roomsData);
            }
        });
    </script>
</body>

</html>
