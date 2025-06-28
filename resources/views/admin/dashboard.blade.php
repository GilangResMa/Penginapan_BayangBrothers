<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/login.css'])
</head>
<body>
    <header class="header">
        <div class="header-container">
            <div class="logo-section">
                <i class="fas fa-home logo-icon"></i>
                <div class="logo-text">Bayang Brothers - Admin</div>
            </div>
            <nav class="navigation">
                <span style="color: white;">Welcome Admin, {{ Auth::guard('admin')->user()->email }}!</span>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: white; color: #dc2626; border: none; padding: 0.5rem 1rem; border-radius: 0.375rem; cursor: pointer;">
                        Logout
                    </button>
                </form>
            </nav>
        </div>
    </header>

    <main style="flex: 1; padding: 2rem; text-align: center;">
        <h1 style="font-size: 2rem; color: #1f2937; margin-bottom: 1rem;">Admin Dashboard</h1>
        <p style="color: #6b7280;">Welcome to the admin control panel!</p>
        <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="#" style="background: #dc2626; color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; text-decoration: none; display: inline-block;">
                Manage Users
            </a>
            <a href="#" style="background: #dc2626; color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; text-decoration: none; display: inline-block;">
                Manage Rooms
            </a>
            <a href="#" style="background: #dc2626; color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; text-decoration: none; display: inline-block;">
                View Bookings
            </a>
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
</body>
</html>
