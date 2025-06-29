// Konfigurasi harga
const WEEKDAY_PRICE = 150000; // Harga hari biasa (Senin-Jumat)
const WEEKEND_PRICE = 180000; // Harga weekend (Sabtu-Minggu)
const EXTRA_BED_PRICE = 70000; // Biaya extra bed per malam (untuk > 2 orang)

// Function untuk mengecek apakah tanggal adalah weekend
function isWeekend(date) {
    const dayOfWeek = date.getDay();
    return dayOfWeek === 0 || dayOfWeek === 6; // 0 = Minggu, 6 = Sabtu
}

// Function untuk menghitung total biaya booking
function calculateBookingCost(checkinDate, checkoutDate, guests = 1) {
    if (!checkinDate || !checkoutDate) return 0;

    const checkin = new Date(checkinDate);
    const checkout = new Date(checkoutDate);
    
    // Validasi tanggal
    if (checkout <= checkin) return 0;

    let totalCost = 0;
    let weekdayNights = 0;
    let weekendNights = 0;
    let extraBedCost = 0;
    
    // Loop untuk setiap malam menginap
    const currentDate = new Date(checkin);
    while (currentDate < checkout) {
        if (isWeekend(currentDate)) {
            weekendNights++;
            totalCost += WEEKEND_PRICE;
        } else {
            weekdayNights++;
            totalCost += WEEKDAY_PRICE;
        }
        currentDate.setDate(currentDate.getDate() + 1);
    }

    // Hitung biaya extra bed jika guests > 2
    const totalNights = weekdayNights + weekendNights;
    const needsExtraBed = parseInt(guests) > 2;
    if (needsExtraBed) {
        extraBedCost = EXTRA_BED_PRICE * totalNights;
        totalCost += extraBedCost;
    }

    return {
        totalCost: totalCost,
        totalNights: totalNights,
        weekdayNights: weekdayNights,
        weekendNights: weekendNights,
        extraBedCost: extraBedCost,
        needsExtraBed: needsExtraBed,
        guests: parseInt(guests)
    };
}

// Function untuk format rupiah
function formatRupiah(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

// Function untuk update tampilan harga
function updatePriceDisplay() {
    const checkinValue = document.getElementById('checkin').value;
    const checkoutValue = document.getElementById('checkout').value;
    const guestsValue = document.getElementById('persons').value;
    
    if (!checkinValue || !checkoutValue) {
        // Reset ke harga default jika tanggal belum dipilih
        document.getElementById('total-price').textContent = formatRupiah(WEEKDAY_PRICE);
        document.getElementById('price-details').style.display = 'none';
        return;
    }

    const result = calculateBookingCost(checkinValue, checkoutValue, guestsValue);
    
    if (result.totalNights === 0) {
        document.getElementById('total-price').textContent = formatRupiah(WEEKDAY_PRICE);
        document.getElementById('price-details').style.display = 'none';
        return;
    }

    // Update total harga
    document.getElementById('total-price').textContent = formatRupiah(result.totalCost);
    
    // Update detail breakdown
    let detailText = `${result.totalNights} malam untuk ${result.guests} orang`;
    
    // Detail harga kamar
    if (result.weekendNights > 0 && result.weekdayNights > 0) {
        detailText += `\n• Kamar: ${result.weekdayNights} hari biasa × ${formatRupiah(WEEKDAY_PRICE)} + ${result.weekendNights} weekend × ${formatRupiah(WEEKEND_PRICE)}`;
    } else if (result.weekendNights > 0) {
        detailText += `\n• Kamar: ${result.weekendNights} malam weekend × ${formatRupiah(WEEKEND_PRICE)}`;
    } else {
        detailText += `\n• Kamar: ${result.weekdayNights} malam hari biasa × ${formatRupiah(WEEKDAY_PRICE)}`;
    }
    
    // Detail extra bed jika ada
    if (result.needsExtraBed) {
        detailText += `\n• Extra Bed: ${result.totalNights} malam × ${formatRupiah(EXTRA_BED_PRICE)}`;
    }
    
    document.getElementById('nights-info').innerHTML = detailText.replace(/\n/g, '<br>');
    document.getElementById('price-details').style.display = 'block';
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

    // Validasi minimal 1 malam
    const result = calculateBookingCost(checkin, checkout, persons);
    if (result.totalNights === 0) {
        alert("Minimal booking 1 malam");
        return false;
    }

    // Konfirmasi jika ada extra bed
    if (result.needsExtraBed) {
        const confirmExtraBed = confirm(
            `Untuk ${persons} orang, akan dikenakan biaya extra bed ${formatRupiah(
                EXTRA_BED_PRICE
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
    const today = new Date().toISOString().split('T')[0];
    const checkinInput = document.getElementById('checkin');
    const checkoutInput = document.getElementById('checkout');
    const personsSelect = document.getElementById('persons');
    
    // Set minimum date
    checkinInput.setAttribute('min', today);
    checkoutInput.setAttribute('min', today);

    // Event listener untuk perubahan tanggal check-in
    checkinInput.addEventListener('change', function() {
        const checkinDate = new Date(this.value);
        checkinDate.setDate(checkinDate.getDate() + 1);
        const minCheckout = checkinDate.toISOString().split('T')[0];
        checkoutInput.setAttribute('min', minCheckout);
        
        // Update harga ketika check-in berubah
        updatePriceDisplay();
    });

    // Event listener untuk perubahan tanggal check-out
    checkoutInput.addEventListener('change', function() {
        updatePriceDisplay();
    });

    // Event listener untuk perubahan jumlah orang
    personsSelect.addEventListener('change', function() {
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
function showExtraBedNotification(show) {
    let notification = document.getElementById('extra-bed-notification');
    
    if (show) {
        if (!notification) {
            // Buat notifikasi jika belum ada
            notification = document.createElement('div');
            notification.id = 'extra-bed-notification';
            notification.className = 'extra-bed-notification';
            notification.innerHTML = `
                <i class="fas fa-info-circle"></i>
                <strong>Extra Bed diperlukan!</strong><br>
                Biaya tambahan ${formatRupiah(EXTRA_BED_PRICE)} per malam untuk lebih dari 2 orang.
            `;
            
            // Insert setelah persons select
            const personsGroup = document.getElementById('persons').closest('.input-group');
            personsGroup.insertAdjacentElement('afterend', notification);
        }
        notification.style.display = 'block';
    } else {
        if (notification) {
            notification.style.display = 'none';
        }
    }
}

// Auto-initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', initializeRoomBooking);
