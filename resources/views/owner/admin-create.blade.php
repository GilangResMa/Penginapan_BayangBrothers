<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Admin - Owner Panel - Bayang Brothers</title>
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
                <a href="{{ route('owner.revenue') }}" class="nav-item">
                    <i class="fas fa-chart-line"></i>
                    Revenue
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

        <!-- Main Content -->
        <main class="main-content">
            <header class="content-header">
                <h1><i class="fas fa-user-plus"></i> Create New Administrator</h1>
                <p>Add a new administrator to help manage your property operations.</p>
            </header>

            <!-- Breadcrumb -->
            <div class="breadcrumb">
                <a href="{{ route('owner.dashboard') }}">Dashboard</a>
                <i class="fas fa-chevron-right"></i>
                <a href="{{ route('owner.admins') }}">Admin Management</a>
                <i class="fas fa-chevron-right"></i>
                <span>Create Admin</span>
            </div>

            <!-- Create Admin Form -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-user-plus"></i>
                    <h3>Administrator Information</h3>
                </div>
                <div class="card-content">
                    <form method="POST" action="/owner/admin" class="admin-form">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-user"></i>
                                Basic Information
                            </h4>
                            
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-user"></i>
                                        Full Name
                                    </label>
                                    <input type="text" 
                                           id="name" 
                                           name="name" 
                                           class="form-input @error('name') error @enderror" 
                                           value="{{ old('name') }}" 
                                           required 
                                           placeholder="Enter administrator's full name">
                                    @error('name')
                                        <span class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope"></i>
                                        Email Address
                                    </label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           class="form-input @error('email') error @enderror" 
                                           value="{{ old('email') }}" 
                                           required 
                                           placeholder="Enter email address">
                                    @error('email')
                                        <span class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Security Information -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-shield-alt"></i>
                                Security Settings
                            </h4>
                            
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-lock"></i>
                                        Password
                                    </label>
                                    <input type="password" 
                                           id="password" 
                                           name="password" 
                                           class="form-input @error('password') error @enderror" 
                                           required 
                                           placeholder="Enter secure password"
                                           minlength="8">
                                    <div class="form-hint">
                                        <i class="fas fa-info-circle"></i>
                                        Password must be at least 8 characters long
                                    </div>
                                    @error('password')
                                        <span class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label">
                                        <i class="fas fa-lock"></i>
                                        Confirm Password
                                    </label>
                                    <input type="password" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           class="form-input" 
                                           required 
                                           placeholder="Confirm password"
                                           minlength="8">
                                    <div class="form-hint">
                                        <i class="fas fa-info-circle"></i>
                                        Re-enter the password to confirm
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Permissions -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-key"></i>
                                Administrator Permissions
                            </h4>
                            
                            <div class="permissions-preview">
                                <div class="permission-item">
                                    <i class="fas fa-calendar-check text-green"></i>
                                    <div class="permission-details">
                                        <h5>Booking Management</h5>
                                        <p>View, confirm, cancel, and manage all customer bookings</p>
                                    </div>
                                    <div class="permission-status">
                                        <i class="fas fa-check-circle text-green"></i>
                                        Included
                                    </div>
                                </div>
                                
                                <div class="permission-item">
                                    <i class="fas fa-bed text-blue"></i>
                                    <div class="permission-details">
                                        <h5>Room Management</h5>
                                        <p>Add, edit, delete rooms and manage room availability</p>
                                    </div>
                                    <div class="permission-status">
                                        <i class="fas fa-check-circle text-green"></i>
                                        Included
                                    </div>
                                </div>
                                
                                <div class="permission-item">
                                    <i class="fas fa-users text-purple"></i>
                                    <div class="permission-details">
                                        <h5>User Management</h5>
                                        <p>View and manage customer accounts and profiles</p>
                                    </div>
                                    <div class="permission-status">
                                        <i class="fas fa-check-circle text-green"></i>
                                        Included
                                    </div>
                                </div>
                                
                                <div class="permission-item">
                                    <i class="fas fa-chart-bar text-orange"></i>
                                    <div class="permission-details">
                                        <h5>Reports & Analytics</h5>
                                        <p>Access booking reports and revenue analytics</p>
                                    </div>
                                    <div class="permission-status">
                                        <i class="fas fa-check-circle text-green"></i>
                                        Included
                                    </div>
                                </div>
                                
                                <div class="permission-item disabled">
                                    <i class="fas fa-crown text-gray"></i>
                                    <div class="permission-details">
                                        <h5>Owner Functions</h5>
                                        <p>Admin management, financial settings, system configuration</p>
                                    </div>
                                    <div class="permission-status">
                                        <i class="fas fa-times-circle text-red"></i>
                                        Restricted
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Status -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-toggle-on"></i>
                                Account Status
                            </h4>
                            
                            <div class="form-group">
                                <div class="checkbox-group">
                                    <input type="checkbox" 
                                           id="status" 
                                           name="status" 
                                           value="1" 
                                           checked 
                                           class="form-checkbox">
                                    <label for="status" class="checkbox-label">
                                        <i class="fas fa-user-check"></i>
                                        Activate administrator account immediately
                                    </label>
                                </div>
                                <div class="form-hint">
                                    <i class="fas fa-info-circle"></i>
                                    If unchecked, the admin account will be created but remain inactive until manually activated.
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <a href="{{ route('owner.admins') }}" class="action-btn outline">
                                <i class="fas fa-arrow-left"></i>
                                Cancel
                            </a>
                            <button type="reset" class="action-btn secondary">
                                <i class="fas fa-undo"></i>
                                Reset Form
                            </button>
                            <button type="submit" class="action-btn primary">
                                <i class="fas fa-user-plus"></i>
                                Create Administrator
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Important Notes</h3>
                </div>
                <div class="card-content">
                    <div class="notes-list">
                        <div class="note-item">
                            <i class="fas fa-shield-alt text-blue"></i>
                            <div class="note-content">
                                <h5>Security</h5>
                                <p>Ensure the email address is valid and secure. The admin will receive login credentials via email.</p>
                            </div>
                        </div>
                        
                        <div class="note-item">
                            <i class="fas fa-key text-orange"></i>
                            <div class="note-content">
                                <h5>Access Level</h5>
                                <p>Administrators have full access to booking and room management but cannot access owner-specific functions.</p>
                            </div>
                        </div>
                        
                        <div class="note-item">
                            <i class="fas fa-edit text-green"></i>
                            <div class="note-content">
                                <h5>Account Management</h5>
                                <p>You can deactivate, edit, or delete administrator accounts at any time from the Admin Management page.</p>
                            </div>
                        </div>
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
</body>

</html>
