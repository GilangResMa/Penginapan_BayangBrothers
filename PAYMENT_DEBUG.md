# Payment Debugging Guide

## Langkah-langkah untuk mengatasi error payment:

### 1. **Cek Konfigurasi Midtrans**
- Pastikan file `.env` memiliki konfigurasi Midtrans yang benar:
```
MIDTRANS_MERCHANT_ID=G812785002
MIDTRANS_CLIENT_KEY=SB-Mid-client-nKsqvar5z5GtqSur
MIDTRANS_SERVER_KEY=SB-Mid-server-GwUP_WGbJPXsDzsOOu6czZfU
MIDTRANS_IS_PRODUCTION=false
```

### 2. **Test Booking Process**
1. Login sebagai user
2. Pilih room di halaman `/room`
3. Isi tanggal check-in dan check-out
4. Klik "Booking Now"
5. Cek browser console untuk error JavaScript
6. Cek log Laravel di `storage/logs/laravel.log`

### 3. **Debug Payment Route**
Akses URL test: `/payment/test/{booking_id}` untuk melihat:
- Data booking
- Konfigurasi Midtrans
- Status koneksi

### 4. **Common Issues & Solutions**

#### Error: "Midtrans server key belum dikonfigurasi"
- Jalankan: `php artisan config:clear`
- Restart server Laravel

#### Error: "Class 'Midtrans\Config' not found"
- Jalankan: `composer require midtrans/midtrans-php`
- Jalankan: `composer dump-autoload`

#### Error: "Route not found"
- Jalankan: `php artisan route:clear`
- Cek apakah route sudah terdaftar: `php artisan route:list | grep payment`

#### Error: "Booking not found"
- Cek apakah table bookings ada: `php artisan migrate`
- Cek data booking di database

### 5. **Testing Commands**
```bash
# Clear all cache
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Check routes
php artisan route:list | grep payment

# Check database
php artisan tinker
>>> \App\Models\Booking::count()
>>> \App\Models\Room::count()

# Test Midtrans configuration
>>> config('midtrans.server_key')
>>> config('midtrans.client_key')
```

### 6. **Logs to Check**
- `storage/logs/laravel.log` - Error Laravel
- Browser Developer Tools Console - Error JavaScript
- Network tab - HTTP requests

### 7. **Midtrans Sandbox Testing**
Gunakan card number test Midtrans:
- Visa: 4811 1111 1111 1114
- Mastercard: 5211 1111 1111 1117
- CVV: 123
- Expiry: 12/25

Untuk testing lebih lanjut, buka: https://simulator.sandbox.midtrans.com/
