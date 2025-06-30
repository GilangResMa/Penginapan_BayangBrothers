# Panduan Menjalankan Aplikasi Laravel dengan Vite

## ❌ MASALAH: CSS Tidak Terbaca

**Error**: File CSS tidak ter-load ketika menjalankan `php artisan serve`

**Penyebab**: Laravel 11 menggunakan Vite untuk asset compilation, jadi CSS perlu di-build terlebih dahulu.

## ✅ SOLUSI LANGKAH DEMI LANGKAH

### 1. Install Dependencies Node.js
```bash
cd c:\laragon\www\PBB
npm install
```

### 2. Build Assets (Untuk Production)
```bash
npm run build
```

**ATAU**

### 2. Development Server (Untuk Development)
```bash
# Terminal 1: Jalankan Vite dev server
npm run dev

# Terminal 2: Jalankan Laravel server
php artisan serve
```

## 🎯 CARA MENJALANKAN APLIKASI

### Opsi A: Production Mode
```bash
# 1. Build assets sekali saja
npm run build

# 2. Jalankan Laravel server
php artisan serve

# 3. Akses http://localhost:8000
```

### Opsi B: Development Mode (Recommended)
```bash
# Terminal 1: Hot reload untuk CSS/JS
npm run dev

# Terminal 2: Laravel server  
php artisan serve

# 3. Akses http://localhost:8000
```

## 📁 STRUKTUR VITE YANG SUDAH DIPERBAIKI

```javascript
// vite.config.js
export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/homepage.css',
                'resources/css/login.css',
                'resources/css/register.css',
                'resources/css/room.css',
                'resources/css/about.css',
                'resources/css/faq.css',
                'resources/css/profile.css',
                'resources/css/admin.css',
                'resources/css/payment.css',
                'resources/css/auth.css',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '127.0.0.1',
        port: 5173,
    },
});
```

## 🔧 TROUBLESHOOTING

### Jika CSS Masih Tidak Muncul:

1. **Clear Cache:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

2. **Rebuild Assets:**
```bash
rm -rf public/build
npm run build
```

3. **Check Node.js Version:**
```bash
node --version  # Minimum v16
npm --version
```

### Jika Ada Error Node.js:
```bash
# Install Node.js dependencies
npm install

# Jika ada error, hapus dan install ulang
rm -rf node_modules
rm package-lock.json
npm install
```

## 📝 FILE BLADE USAGE

Pastikan di file blade menggunakan:
```blade
@vite(['resources/css/homepage.css'])
```

**BUKAN:**
```html
<link rel="stylesheet" href="/css/homepage.css">
```

## 🚀 QUICK START COMMANDS

```bash
# Masuk ke direktori project
cd c:\laragon\www\PBB

# Install dependencies (hanya sekali)
npm install

# Jalankan development mode
npm run dev &
php artisan serve

# Akses: http://localhost:8000
```

## ✅ HASIL YANG DIHARAPKAN

Setelah menjalankan langkah di atas:
- ✅ CSS homepage terbaca dengan baik
- ✅ CSS login/register berfungsi
- ✅ CSS admin panel terload
- ✅ CSS owner dashboard aktif
- ✅ Hot reload berfungsi (development mode)

## 📊 STATUS WEBSITE

**Fitur yang Siap:**
- 🔒 Security middleware (aktif)
- 👤 Multi-auth (owner/admin/user)
- 🎨 CSS styling (setelah build)
- 📱 Responsive design
- 🛡️ Input validation

**Website PBB siap digunakan dengan tampilan yang sempurna! 🎉**
