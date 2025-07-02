<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Management - Owner Panel - Bayang Brothers</title>
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
                <a href="{{ route('owner.admins') }}" class="nav-item active">
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
                <h1><i class="fas fa-user-shield"></i> Admin Management</h1>
                <p>Manage administrative users and their permissions for your property.</p>
            </header>

            <!-- Admin Statistics -->
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-users"></i>
                        <h3>Total Admins</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">{{ $admins->total() }}</div>
                        <div class="stat-label">Registered administrators</div>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-user-check"></i>
                        <h3>Active Admins</h3>
                    </div>
                    <div class="card-content">
                        @php
                            $activeCount = $admins->where('status', true)->count();
                        @endphp
                        <div class="stat-number">{{ $activeCount }}</div>
                        <div class="stat-label">Currently active</div>
                    </div>
                </div>
            </div>

            <!-- Add New Admin -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-user-plus"></i>
                    <h3>Add New Administrator</h3>
                </div>
                <div class="card-content">
                    <div class="action-buttons">
                        <a href="{{ route('owner.admin.create') }}" class="action-btn primary">
                            <i class="fas fa-plus"></i>
                            Create New Admin
                        </a>
                        <p class="help-text">
                            Add new administrators to help manage your property bookings and operations.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Admins List -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-list"></i>
                    <h3>Current Administrators</h3>
                    <div class="card-actions">
                        <span class="badge">{{ $admins->count() ?? 0 }} admins</span>
                    </div>
                </div>
                <div class="card-content">
                    @if(isset($admins) && $admins->count() > 0)
                        <div class="admin-grid">
                            @foreach($admins as $admin)
                            <div class="admin-card">
                                <div class="admin-avatar">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <div class="admin-info">
                                    <h4 class="admin-name">{{ $admin->name }}</h4>
                                    <p class="admin-email">{{ $admin->email }}</p>
                                    <div class="admin-meta">
                                        <span class="admin-role">
                                            <i class="fas fa-tag"></i>
                                            Administrator
                                        </span>                        <span class="admin-status status-{{ $admin->status ? 'active' : 'inactive' }}">
                            <i class="fas fa-circle"></i>
                            {{ $admin->status ? 'Active' : 'Inactive' }}
                        </span>
                                    </div>
                                    <div class="admin-dates">
                                        <div class="date-item">
                                            <span class="date-label">Created:</span>
                                            <span class="date-value">{{ $admin->created_at->format('d M Y') }}</span>
                                        </div>
                                        <div class="date-item">
                                            <span class="date-label">Last Updated:</span>
                                            <span class="date-value">{{ $admin->updated_at->format('d M Y, H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="admin-actions">
                                    @if($admin->status)
                                        <form method="POST" action="{{ route('owner.admin.update', $admin->id) }}" style="display: inline;">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="action" value="deactivate">
                                            <button type="submit" class="btn-small btn-warning" title="Deactivate Admin" 
                                                    onclick="return confirm('Are you sure you want to deactivate this admin?')">
                                                <i class="fas fa-user-slash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('owner.admin.update', $admin->id) }}" style="display: inline;">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="action" value="activate">
                                            <button type="submit" class="btn-small btn-success" title="Activate Admin">
                                                <i class="fas fa-user-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form method="POST" action="{{ route('owner.admin.delete', $admin->id) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-small btn-danger" title="Delete Admin" 
                                                onclick="return confirm('Are you sure you want to permanently delete this admin? This action cannot be undone.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-user-shield"></i>
                            <h4>No Administrators</h4>
                            <p>You haven't added any administrators yet. Create your first admin to help manage your property.</p>
                            <a href="{{ route('owner.admin.create') }}" class="action-btn primary">
                                <i class="fas fa-user-plus"></i>
                                Create First Admin
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Admin Permissions Info -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i>
                    <h3>Administrator Permissions</h3>
                </div>
                <div class="card-content">
                    <div class="permissions-info">
                        <div class="permission-item">
                            <i class="fas fa-calendar-check text-green"></i>
                            <div class="permission-details">
                                <h4>Booking Management</h4>
                                <p>View, confirm, and cancel customer bookings</p>
                            </div>
                        </div>
                        
                        <div class="permission-item">
                            <i class="fas fa-bed text-blue"></i>
                            <div class="permission-details">
                                <h4>Room Management</h4>
                                <p>Add, edit, and manage room listings and availability</p>
                            </div>
                        </div>
                        
                        <div class="permission-item">
                            <i class="fas fa-users text-purple"></i>
                            <div class="permission-details">
                                <h4>User Management</h4>
                                <p>View and manage customer accounts and profiles</p>
                            </div>
                        </div>
                        
                        <div class="permission-item">
                            <i class="fas fa-chart-bar text-orange"></i>
                            <div class="permission-details">
                                <h4>Reports & Analytics</h4>
                                <p>Access booking reports and revenue analytics</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-note">
                        <i class="fas fa-lightbulb"></i>
                        <p><strong>Note:</strong> Administrators have full access to all booking and room management features, but cannot access owner-specific functions like admin management or financial settings.</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-lightning-bolt"></i>
                    <h3>Quick Actions</h3>
                </div>
                <div class="card-content">
                    <div class="action-buttons">
                        <a href="{{ route('owner.dashboard') }}" class="action-btn secondary">
                            <i class="fas fa-tachometer-alt"></i>
                            Back to Dashboard
                        </a>
                        <a href="{{ route('owner.admin.create') }}" class="action-btn primary">
                            <i class="fas fa-user-plus"></i>
                            Add New Admin
                        </a>
                        <a href="{{ route('owner.bookings') }}" class="action-btn outline">
                            <i class="fas fa-calendar-check"></i>
                            View Bookings
                        </a>
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
        // Mobile menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
            const sidebar = document.querySelector('.sidebar');

            if (mobileMenuToggle && mobileMenuOverlay && sidebar) {
                mobileMenuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    mobileMenuOverlay.classList.toggle('active');
                    this.classList.toggle('active');
                });

                mobileMenuOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    this.classList.remove('active');
                    mobileMenuToggle.classList.remove('active');
                });

                // Close mobile menu when window resizes to desktop size
                window.addEventListener('resize', function() {
                    if (window.innerWidth > 768) {
                        sidebar.classList.remove('active');
                        mobileMenuOverlay.classList.remove('active');
                        mobileMenuToggle.classList.remove('active');
                    }
                });
            }
        });
    </script>
</body>

</html>
