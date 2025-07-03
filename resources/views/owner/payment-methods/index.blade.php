<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payment Methods</title>
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
                <h1><i class="fas fa-money-check-alt"></i> Payment Methods</h1>
                <p>Manage your payment methods for customer bookings</p>
                <div class="header-actions">
                    <a href="{{ route('owner.payment-methods.create') }}" class="action-btn primary">
                        <i class="fas fa-plus"></i>
                        Add Payment Method
                    </a>
                </div>
            </header>

            <!-- Bank Transfer Methods -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-university"></i>
                    <h3>Bank Transfer Methods</h3>
                </div>
                <div class="card-content">
                    <div class="payment-methods-grid">
                        @forelse($bankMethods as $method)
                            <div class="payment-method-card {{ $method->is_active ? 'active' : 'inactive' }}">
                                <div class="method-header">
                                    <h4>{{ $method->name }}</h4>
                                    <span class="status-badge status-{{ $method->is_active ? 'active' : 'inactive' }}">
                                        <i class="fas fa-circle"></i>
                                        {{ $method->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <div class="method-details">
                                    <p><strong>Bank Name:</strong> {{ $method->bank_name }}</p>
                                    <p><strong>Account Number:</strong> {{ $method->formatted_account_number ?? $method->account_number }}</p>
                                    <p><strong>Account Name:</strong> {{ $method->account_name }}</p>
                                    @if($method->description)
                                        <p class="method-description">{{ $method->description }}</p>
                                    @endif
                                </div>
                                <div class="method-actions">
                                    <a href="{{ route('owner.payment-methods.edit', $method->id) }}" class="btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('owner.payment-methods.delete', $method->id) }}" 
                                          onsubmit="return confirm('Are you sure you want to delete this payment method?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="fas fa-university"></i>
                                <h4>No Bank Transfer Methods</h4>
                                <p>You haven't added any bank transfer methods yet.</p>
                                <a href="{{ route('owner.payment-methods.create') }}?type=bank" class="action-btn primary">
                                    <i class="fas fa-plus"></i>
                                    Add Bank Transfer
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- QRIS Methods -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-qrcode"></i>
                    <h3>QRIS Methods</h3>
                </div>
                <div class="card-content">
                    <div class="payment-methods-grid">
                        @forelse($qrisMethods as $method)
                            <div class="payment-method-card {{ $method->is_active ? 'active' : 'inactive' }}">
                                <div class="method-header">
                                    <h4>{{ $method->name }}</h4>
                                    <span class="status-badge status-{{ $method->is_active ? 'active' : 'inactive' }}">
                                        <i class="fas fa-circle"></i>
                                        {{ $method->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <div class="method-details">
                                    @if($method->qr_image)
                                        <div class="qr-preview">
                                            <img src="{{ $method->qr_image_url }}" alt="QRIS Code">
                                        </div>
                                    @else
                                        <div class="empty-qr">
                                            <i class="fas fa-qrcode"></i>
                                            <p>No QR image uploaded</p>
                                        </div>
                                    @endif
                                    @if($method->description)
                                        <p class="method-description">{{ $method->description }}</p>
                                    @endif
                                </div>
                                <div class="method-actions">
                                    <a href="{{ route('owner.payment-methods.edit', $method->id) }}" class="btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('owner.payment-methods.delete', $method->id) }}" 
                                          onsubmit="return confirm('Are you sure you want to delete this payment method?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="fas fa-qrcode"></i>
                                <h4>No QRIS Methods</h4>
                                <p>You haven't added any QRIS payment methods yet.</p>
                                <a href="{{ route('owner.payment-methods.create') }}?type=qris" class="action-btn primary">
                                    <i class="fas fa-plus"></i>
                                    Add QRIS Payment
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Payment Method Guide -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i>
                    <h3>Payment Method Guidelines</h3>
                </div>
                <div class="card-content">
                    <div class="guide-section">
                        <h4><i class="fas fa-university"></i> Bank Transfer Guidelines</h4>
                        <ul class="guide-list">
                            <li>
                                <i class="fas fa-check-circle text-success"></i>
                                <span>Provide accurate bank account details for customers</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle text-success"></i>
                                <span>Include the full account holder name as it appears on the bank account</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle text-success"></i>
                                <span>Double-check account numbers to avoid payment issues</span>
                            </li>
                        </ul>

                        <h4><i class="fas fa-qrcode"></i> QRIS Guidelines</h4>
                        <ul class="guide-list">
                            <li>
                                <i class="fas fa-check-circle text-success"></i>
                                <span>Upload a high-quality, clear QR code image</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle text-success"></i>
                                <span>Ensure the QR code is scannable by standard banking and e-wallet apps</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle text-success"></i>
                                <span>Provide clear instructions in the description field if needed</span>
                            </li>
                        </ul>
                    </div>
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
            
            // Card hover effect
            const cards = document.querySelectorAll('.payment-method-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                });
            });
        });
    </script>
</body>

</html>
