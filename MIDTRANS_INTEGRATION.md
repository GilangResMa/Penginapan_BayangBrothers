# Integrasi Payment Gateway Midtrans - Bayang Brothers

## Deskripsi
Implementasi integrasi payment gateway Midtrans untuk sistem booking room Bayang Brothers. Integrasi ini memungkinkan user untuk melakukan pembayaran menggunakan berbagai metode pembayaran yang disediakan oleh Midtrans.

## Fitur Payment Gateway

### Metode Pembayaran yang Didukung
1. **Bank Transfer**
   - Virtual Account BCA
   - Virtual Account BNI
   - Virtual Account BRI
   - Mandiri Bill Payment

2. **Credit Card**
   - Visa
   - MasterCard
   - JCB
   - Secure 3D authentication

3. **Digital Wallet**
   - GoPay
   - ShopeePay
   - DANA (via QRIS)
   - OVO (via QRIS)

### Flow Pembayaran
1. User memilih kamar dan melakukan booking
2. Diarahkan ke halaman payment
3. User memilih metode pembayaran
4. Sistem membuat snap token via Midtrans API
5. User melakukan pembayaran melalui popup Midtrans Snap
6. Sistem menerima notifikasi callback dari Midtrans
7. Status booking diupdate otomatis
8. User menerima konfirmasi pembayaran

## Setup & Konfigurasi

### 1. Install Dependencies
```bash
composer require midtrans/midtrans-php
```

### 2. Konfigurasi Environment
Tambahkan konfigurasi berikut ke file `.env`:

```bash
# Midtrans Payment Gateway Configuration
MIDTRANS_MERCHANT_ID=your_merchant_id
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

### 3. Database Migration
```bash
php artisan migrate
```
Migration akan menambahkan kolom berikut ke tabel `bookings`:
- `snap_token` - Token untuk Midtrans Snap
- `order_id` - Unique order ID untuk tracking
- `transaction_id` - ID transaksi dari Midtrans
- `payment_type` - Jenis pembayaran yang digunakan
- `payment_time` - Waktu pembayaran berhasil

### 4. Konfigurasi Web Server
Pastikan callback URL dapat diakses dari Midtrans:
- `POST /midtrans/notification` - Untuk notification callback
- `GET /payment/finish/{booking}` - Redirect setelah pembayaran sukses
- `GET /payment/unfinish/{booking}` - Redirect jika pembayaran belum selesai
- `GET /payment/error/{booking}` - Redirect jika pembayaran error

## File-file yang Ditambahkan/Dimodifikasi

### Controllers
- `PaymentController.php` - Ditambahkan metode untuk Midtrans integration

### Views
- `payment.blade.php` - Updated untuk integrasi Midtrans Snap
- `payment-pending.blade.php` - Halaman status pembayaran pending

### Assets
- `resources/js/midtrans-payment.js` - JavaScript untuk handling Midtrans Snap
- `resources/css/midtrans-payment.css` - Styling untuk payment interface

### Routes
- API routes untuk create snap token
- Callback routes untuk Midtrans notifications

### Migration
- `add_midtrans_fields_to_bookings_table.php` - Menambah kolom untuk Midtrans

## Security Features

### 1. CSRF Protection
Semua request menggunakan CSRF token untuk keamanan.

### 2. Authentication
API endpoints memerlukan user authentication.

### 3. Data Validation
Semua input divalidasi sebelum diproses.

### 4. Logging
Semua aktivitas payment dicatat untuk monitoring dan debugging.

### 5. Content Security Policy
CSP headers dikonfigurasi untuk mengizinkan Midtrans domains.

## Testing

### 1. Test Midtrans Configuration
Akses URL `/test-midtrans` untuk mengecek konfigurasi Midtrans.

### 2. Sandbox Testing
Gunakan `MIDTRANS_IS_PRODUCTION=false` untuk testing dengan sandbox environment.

### 3. Test Payment Methods
- Test setiap metode pembayaran
- Verify callback notifications
- Check booking status updates

## Status Pembayaran

### Status Booking yang Didukung
- `pending` - Booking baru, belum bayar
- `awaiting_payment` - Menunggu pembayaran
- `confirmed` - Pembayaran berhasil, booking dikonfirmasi
- `failed` - Pembayaran gagal
- `expired` - Pembayaran expired
- `cancelled` - Booking dibatalkan
- `challenge` - Pembayaran dalam review (fraud detection)

## Troubleshooting

### 1. Midtrans Snap Not Loading
- Check client key configuration
- Verify CSP headers
- Check network connectivity

### 2. Payment Callback Not Working
- Verify webhook URL accessibility
- Check server key configuration
- Review notification logs

### 3. Database Issues
- Run migration if columns missing
- Check database permissions
- Verify connection settings

## Support & Documentation

### Midtrans Documentation
- [Midtrans Snap Documentation](https://docs.midtrans.com/en/snap/overview)
- [Midtrans API Reference](https://docs.midtrans.com/en/api-reference/overview)
- [Payment Methods](https://docs.midtrans.com/en/core-api/payment-methods)

### Contact
- Email: support@bayangbrothers.com
- WhatsApp: +62 813-9264-0030

## Changelog

### v1.0.0 (2025-07-01)
- Initial Midtrans integration
- Support for Bank Transfer, Credit Card, and Digital Wallet
- Automatic payment status updates
- Payment pending page
- Security enhancements
- Comprehensive logging
