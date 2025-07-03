<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Payment Method</title>
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
                <h1><i class="fas fa-edit"></i> Edit Payment Method</h1>
                <p>Update your {{ $method->type === 'bank' ? 'bank transfer' : 'QRIS' }} payment method</p>
            </header>

            <!-- Edit Payment Method Form -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-{{ $method->type === 'bank' ? 'university' : 'qrcode' }}"></i>
                    <h3>Edit {{ $method->type === 'bank' ? 'Bank Transfer' : 'QRIS' }} Method</h3>
                    <div class="card-actions">
                        <span class="status-badge status-{{ $method->is_active ? 'active' : 'inactive' }}">
                            <i class="fas fa-circle"></i>
                            {{ $method->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <div class="card-content">
                    <form method="POST" action="{{ route('owner.payment-methods.update', $method->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">Payment Method Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $method->name) }}" required>
                            @error('name')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bank-specific fields -->
                        @if($method->type === 'bank')
                            <div class="form-section">
                                <div class="form-section-title">Bank Transfer Details</div>
                                <div class="form-group">
                                    <label for="bank_name">Bank Name</label>
                                    <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name', $method->bank_name) }}" required>
                                    @error('bank_name')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="account_number">Account Number</label>
                                    <input type="text" id="account_number" name="account_number" value="{{ old('account_number', $method->account_number) }}" required pattern="[0-9]*">
                                    @error('account_number')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="account_name">Account Holder Name</label>
                                    <input type="text" id="account_name" name="account_name" value="{{ old('account_name', $method->account_name) }}" required>
                                    @error('account_name')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        <!-- QRIS-specific fields -->
                        @if($method->type === 'qris')
                            <div class="form-group">
                                <label for="qr_image">QR Code Image</label>
                                <div class="file-upload-container">
                                    <input type="file" id="qr_image" name="qr_image" @change="showImagePreview($event)">
                                    <label for="qr_image" class="file-upload-label">
                                        <i class="fas fa-upload"></i>
                                        <span>Choose New QRIS QR Code</span>
                                    </label>
                                </div>
                                
                                <div class="file-upload-preview" id="qrImagePreview">
                                    @if($method->qr_image)
                                        <div class="current-image">
                                            <h4>Current QR Image</h4>
                                            <img src="{{ $method->qr_image_url }}" alt="Current QR Code">
                                        </div>
                                    @endif
                                    <div class="new-image" id="newImagePreview" style="display: none;">
                                        <h4>New QR Image</h4>
                                        <img src="" alt="New QR Code Preview">
                                    </div>
                                </div>
                                
                                @error('qr_image')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                                <p class="help-text">Upload a new QR code image or leave empty to keep the current one</p>
                            </div>
                            
                            <script>
                                function showImagePreview(event) {
                                    const input = event.target;
                                    if (input.files && input.files[0]) {
                                        const reader = new FileReader();
                                        
                                        reader.onload = function(e) {
                                            const preview = document.getElementById('newImagePreview');
                                            const previewImg = preview.querySelector('img');
                                            previewImg.src = e.target.result;
                                            preview.style.display = 'block';
                                        }
                                        
                                        reader.readAsDataURL(input.files[0]);
                                    }
                                }
                            </script>
                        @endif

                        <div class="form-group">
                            <label for="description">Description (Optional)</label>
                            <textarea id="description" name="description" rows="3">{{ old('description', $method->description) }}</textarea>
                            @error('description')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-checkbox">
                                <input type="checkbox" id="is_active" name="is_active" {{ $method->is_active ? 'checked' : '' }}>
                                <label for="is_active">Active</label>
                            </div>
                            <p class="help-text">Uncheck to disable this payment method from being shown to customers</p>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="action-btn primary">
                                <i class="fas fa-save"></i>
                                Update Payment Method
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
