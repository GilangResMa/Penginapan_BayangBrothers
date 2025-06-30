# Payment Page Documentation

## Overview
Payment page yang telah dibuat menyediakan fungsi lengkap untuk review booking dan pembayaran melalui Midtrans dengan semua metode pembayaran Indonesia yang populer.

## Features

### 1. Booking Review
- **Room Details**: Gambar, nama, deskripsi, dan fasilitas kamar
- **Booking Information**: 
  - Booking code
  - Check-in/Check-out dates
  - Duration (number of nights)
  - Number of guests
  - Extra bed (if selected)
- **Price Breakdown**: 
  - Weekday pricing
  - Weekend pricing
  - Extra bed costs
  - Total amount

### 2. Payment Methods (via Midtrans)
- **Credit/Debit Cards**: Visa, Mastercard, JCB
- **Bank Transfer**: BCA, BNI, BRI, Mandiri, CIMB Niaga, Permata
- **E-Wallets**: GoPay, ShopeePay, DANA, OVO, LinkAja
- **Convenience Stores**: Indomaret, Alfamart

### 3. Security Features
- **Secure Payment Gateway**: All payments processed through Midtrans
- **Payment Expiry**: 24-hour payment window
- **User Authentication**: Only booking owner can access payment page
- **Booking Status Validation**: Only pending bookings can be paid

## File Structure

```
resources/
├── views/
│   └── payment.blade.php          # Main payment page
├── css/
│   └── payment.css                # Payment page styles (red theme)
```

```
app/Http/Controllers/
└── PaymentController.php          # Handles payment logic
```

```
routes/
└── web.php                        # Payment routes
```

```
config/
└── midtrans.php                   # Midtrans configuration
```

## Routes

### Payment Routes (Requires Authentication)
```php
Route::middleware('auth:web')->group(function () {
    Route::get('/payment/{booking}', [PaymentController::class, 'show'])->name('payment');
    Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');
    Route::get('/payment/unfinish', [PaymentController::class, 'unfinish'])->name('payment.unfinish');
    Route::get('/payment/error', [PaymentController::class, 'error'])->name('payment.error');
    Route::get('/payment/test/{booking}', ...)->name('payment.test'); // Debug route
});

// Midtrans notification (no auth required)
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');
```

## Usage Flow

### 1. Access Payment Page
```
URL: /payment/{booking_id}
Authentication: Required (web guard)
```

### 2. Payment Process
1. User reviews booking details
2. User clicks "Proceed to Payment" button
3. Midtrans Snap popup opens with payment options
4. User selects payment method and completes payment
5. User redirected based on payment result:
   - Success: `/payment/finish` → Redirected to profile with success message
   - Pending: `/payment/unfinish` → Redirected to profile with info message
   - Error: `/payment/error` → Redirected to profile with error message

### 3. Payment Status Updates
- Midtrans sends webhook notifications to `/payment/notification`
- Booking status automatically updated based on payment status:
  - `settlement`/`capture` → `confirmed`
  - `pending` → `pending`
  - `deny`/`expire`/`cancel` → `cancelled`

## Configuration

### Environment Variables (.env)
```env
# Midtrans Configuration (Sandbox/Development)
MIDTRANS_MERCHANT_ID=G812785002
MIDTRANS_CLIENT_KEY=SB-Mid-client-nKsqvar5z5GtqSur
MIDTRANS_SERVER_KEY=SB-Mid-server-GwUP_WGbJPXsDzsOOu6czZfU
MIDTRANS_IS_PRODUCTION=false
```

### Production Setup
For production, change:
```env
MIDTRANS_IS_PRODUCTION=true
# Update with production keys
MIDTRANS_CLIENT_KEY=Mid-client-xxxxx
MIDTRANS_SERVER_KEY=Mid-server-xxxxx
```

## Error Handling

### Common Issues and Solutions

1. **"Midtrans server key belum dikonfigurasi"**
   - Solution: Check .env file for MIDTRANS_SERVER_KEY
   - Run: `php artisan config:cache`

2. **"Booking tidak ditemukan"**
   - Solution: Ensure user owns the booking
   - Check booking ID in URL

3. **"Booking ini sudah diproses"**
   - Solution: Only pending bookings can be paid
   - Check booking status in database

4. **Payment popup doesn't open**
   - Solution: Check browser console for JavaScript errors
   - Ensure Midtrans Snap script is loaded

## Testing

### Test Payment
1. Create a booking through the room booking system
2. Access payment page: `/payment/{booking_id}`
3. Use Midtrans sandbox test cards:
   - Success: `4811 1111 1111 1114`
   - Failure: `4911 1111 1111 1113`

### Debug Route
```
URL: /payment/test/{booking_id}
Returns: JSON with booking details and Midtrans config status
```

## Design Theme

### Red Theme Consistency
- **Primary Color**: #dc2626 (red-600)
- **Secondary Color**: #b91c1c (red-700)
- **Accent Color**: #991b1b (red-800)
- **Background**: Linear gradient from #f5f7fa to #c3cfe2
- **Cards**: White with red accents and hover effects

### Responsive Design
- **Desktop**: Two-column layout (booking summary + payment methods)
- **Mobile**: Single-column layout with stacked cards
- **Navigation**: Collapses to icons on mobile

## Security Notes

1. **Server Key**: Never expose server key in client-side code
2. **Webhook Validation**: Midtrans webhook notifications are validated
3. **User Authentication**: Payment access restricted to booking owner
4. **HTTPS**: Use HTTPS in production for secure payment processing

## Maintenance

### Regular Tasks
1. Monitor payment logs: `storage/logs/laravel.log`
2. Check booking status updates
3. Verify Midtrans webhook deliveries
4. Update payment method logos/information as needed

### Database Monitoring
```sql
-- Check pending payments older than 24 hours
SELECT * FROM bookings 
WHERE status = 'pending' 
AND created_at < NOW() - INTERVAL 24 HOUR;

-- Payment success rate
SELECT 
    status,
    COUNT(*) as count,
    ROUND(COUNT(*) * 100.0 / SUM(COUNT(*)) OVER(), 2) as percentage
FROM bookings 
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY status;
```

## API Endpoints

### PaymentController Methods

```php
// Show payment page
GET /payment/{booking}
Response: Blade view with booking details and Snap token

// Handle successful payment
GET /payment/finish?order_id=xxx&status_code=xxx&transaction_status=xxx
Response: Redirect to profile with success message

// Handle unfinished payment
GET /payment/unfinish?order_id=xxx
Response: Redirect to profile with info message

// Handle payment error
GET /payment/error?order_id=xxx
Response: Redirect to profile with error message

// Midtrans webhook notification
POST /payment/notification
Body: Midtrans notification data
Response: JSON success/error
```

## Customization

### Adding Payment Methods
Midtrans automatically provides all available Indonesian payment methods. To customize:

1. **Enable/Disable Methods**: Configure in Midtrans dashboard
2. **Update UI**: Modify `payment.blade.php` to reflect available methods
3. **Styling**: Update `payment.css` for custom method appearance

### Custom Payment Logic
Override PaymentController methods:
```php
// Custom transaction data
protected function buildTransactionData($booking) {
    // Custom implementation
}

// Custom success handling
public function handleSuccess($result) {
    // Custom implementation
}
```

This payment system provides a complete, secure, and user-friendly payment experience that integrates seamlessly with the existing Bayang Brothers booking system.
