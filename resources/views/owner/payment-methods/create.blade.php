<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Payment Method</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/owner.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
</head>

<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-crown logo-icon"></i>
                <h2>Owner Panel</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('owner.dashboard') }}" class="nav-item">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
                <a href="{{ route('owner.bookings') }}" class="nav-item">
                    <i class="fas fa-calendar-check"></i>
                    Bookings
                </a>
                <a href="{{ route('owner.payments') }}" class="nav-item">
                    <i class="fas fa-credit-card"></i>
                    Payments
                </a>
                <a href="{{ route('owner.users') }}" class="nav-item">
                    <i class="fas fa-users"></i>
                    Customers
                </a>
                <a href="{{ route('owner.revenue') }}" class="nav-item">
                    <i class="fas fa-chart-line"></i>
                    Revenue Report
                </a>
                <a href="{{ route('owner.payment-methods') }}" class="nav-item active">
                    <i class="fas fa-money-check-alt"></i>
                    Payment Methods
                </a>
                <a href="{{ route('owner.admins') }}" class="nav-item">
                    <i class="fas fa-user-shield"></i>
                    Admin Management
                </a>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="nav-item logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle" id="mobileMenuToggle">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>

        <!-- Mobile Menu Overlay -->
        <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>

        <!-- Main Content -->
        <main class="main-content">
            <header class="content-header">
                <h1><i class="fas fa-plus-circle"></i> Create Payment Method</h1>
                <p>Add a new payment method for your customers</p>
            </header>

            <!-- Create Payment Method Form -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-money-check-alt"></i>
                    <h3>Payment Method Details</h3>
                </div>
                <div class="card-content">
                    <form method="POST" action="{{ route('owner.payment-methods.store') }}" enctype="multipart/form-data" x-data="{ paymentType: '{{ request()->query('type', 'bank') }}' }">
                        @csrf

                        <div class="form-group">
                            <label for="type">Payment Method Type</label>
                            <div class="payment-type-selector">
                                <label class="payment-type-option" :class="{ 'active': paymentType === 'bank' }">
                                    <input type="radio" name="type" value="bank" x-model="paymentType">
                                    <i class="fas fa-university"></i>
                                    <span>Bank Transfer</span>
                                </label>
                                <label class="payment-type-option" :class="{ 'active': paymentType === 'qris' }">
                                    <input type="radio" name="type" value="qris" x-model="paymentType">
                                    <i class="fas fa-qrcode"></i>
                                    <span>QRIS</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name">Payment Method Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-input" required>
                            @error('name')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                            <p class="help-text">e.g. "Bank BCA", "Bank Mandiri", "QRIS Payment"</p>
                        </div>

                        <!-- Bank-specific fields -->
                        <div x-show="paymentType === 'bank'">
                            <div class="form-section">
                                <div class="form-section-title">Bank Transfer Details</div>
                                <div class="form-group">
                                    <label for="bank_name">Bank Name</label>
                                    <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" class="form-input">
                                    @error('bank_name')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="account_number">Account Number</label>
                                    <input type="text" id="account_number" name="account_number" value="{{ old('account_number') }}" class="form-input" pattern="[0-9]*">
                                    @error('account_number')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="account_name">Account Holder Name</label>
                                    <input type="text" id="account_name" name="account_name" value="{{ old('account_name') }}" class="form-input">
                                    @error('account_name')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- QRIS-specific fields -->
                        <div class="form-group" x-show="paymentType === 'qris'">
                            <label for="qr_image">QR Code Image</label>
                            <div class="file-upload-container">
                                <input type="file" id="qr_image" name="qr_image" x-ref="qrImageInput" 
                                       @change="showImagePreview($refs.qrImageInput)" accept="image/*">
                                <label for="qr_image" class="file-upload-label">
                                    <i class="fas fa-upload"></i>
                                    <span>Choose QRIS QR Code</span>
                                </label>
                            </div>
                            <div class="file-upload-preview" id="qrImagePreview" x-show="$refs.qrImagePreview" style="display: none;">
                                <img src="" alt="QR Code Preview" x-ref="qrImagePreview">
                            </div>
                            @error('qr_image')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                            <p class="help-text">Upload a clear QR code image for QRIS payments (JPEG, PNG, max 2MB)</p>
                        </div>
                        
                        <script>
                            function showImagePreview(input) {
                                if (input.files && input.files[0]) {
                                    const reader = new FileReader();
                                    
                                    reader.onload = function(e) {
                                        const preview = document.getElementById('qrImagePreview');
                                        const previewImg = document.querySelector('#qrImagePreview img');
                                        previewImg.src = e.target.result;
                                        preview.style.display = 'block';
                                    }
                                    
                                    reader.readAsDataURL(input.files[0]);
                                }
                            }
                        </script>

                        <div class="form-group">
                            <label for="description">Description (Optional)</label>
                            <textarea id="description" name="description" class="form-input" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                            <p class="help-text">Add instructions or additional information for this payment method</p>
                        </div>

                        <div class="form-group">
                            <div class="form-checkbox">
                                <input type="checkbox" id="is_active" name="is_active" checked>
                                <label for="is_active">Active</label>
                            </div>
                            <p class="help-text">Uncheck to disable this payment method from being shown to customers</p>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="action-btn primary">
                                <i class="fas fa-save"></i>
                                Save Payment Method
                            </button>
                            <a href="{{ route('owner.payment-methods') }}" class="action-btn outline">
                                <i class="fas fa-arrow-left"></i>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    @if(session('success'))
        <div class="alert alert-success" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
            const sidebar = document.querySelector('.sidebar');
            
            if (mobileMenuToggle && mobileMenuOverlay && sidebar) {
                mobileMenuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('mobile-visible');
                    mobileMenuOverlay.classList.toggle('visible');
                    document.body.classList.toggle('menu-open');
                });
                
                mobileMenuOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('mobile-visible');
                    mobileMenuOverlay.classList.remove('visible');
                    document.body.classList.remove('menu-open');
                });
            }
        });
    </script>
</body>

</html>
