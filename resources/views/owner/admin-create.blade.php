<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Admin - Bayang Brothers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/admin.css'])
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
                    Manage Bookings
                </a>
                <a href="{{ route('owner.revenue') }}" class="nav-item">
                    <i class="fas fa-chart-line"></i>
                    Revenue Analytics
                </a>
                <a href="{{ route('owner.admins') }}" class="nav-item active">
                    <i class="fas fa-users-cog"></i>
                    Manage Admins
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
                <h1>Create New Admin</h1>
                <p>Add a new administrator to manage the system</p>
            </header>
            <!-- Navigation Breadcrumb -->
            <div class="dashboard-card">
                <div class="card-content">
                    <nav class="breadcrumb">
                        <a href="{{ route('owner.dashboard') }}">
                            <i class="fas fa-home"></i>
                            Dashboard
                        </a>
                        <span class="separator">/</span>
                        <a href="{{ route('owner.admins') }}">
                            <i class="fas fa-users-cog"></i>
                            Manage Admins
                        </a>
                        <span class="separator">/</span>
                        <span class="current">Create Admin</span>
                    </nav>
                </div>
            </div>

            <!-- Create Admin Form -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-user-plus"></i>
                    <h3>Admin Information</h3>
                </div>
                <div class="card-content">
                    <form method="POST" action="{{ route('owner.admin.store') }}" class="admin-form">
                        @csrf
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name">
                                    <i class="fas fa-user"></i>
                                    Full Name
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required 
                                       class="form-control"
                                       placeholder="Enter admin's full name">
                                @error('name')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">
                                    <i class="fas fa-envelope"></i>
                                    Email Address
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required 
                                       class="form-control"
                                       placeholder="Enter email address">
                                @error('email')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone">
                                    <i class="fas fa-phone"></i>
                                    Phone Number
                                </label>
                                <input type="text" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone') }}" 
                                       class="form-control"
                                       placeholder="Enter phone number (optional)">
                                @error('phone')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="status">
                                    <i class="fas fa-toggle-on"></i>
                                    Status
                                </label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">
                                    <i class="fas fa-lock"></i>
                                    Password
                                </label>
                                <div class="password-input">
                                    <input type="password" 
                                           id="password" 
                                           name="password" 
                                           required 
                                           class="form-control"
                                           placeholder="Enter secure password"
                                           minlength="8">
                                    <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <small class="form-help">Password must be at least 8 characters long</small>
                                @error('password')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">
                                    <i class="fas fa-lock"></i>
                                    Confirm Password
                                </label>
                                <div class="password-input">
                                    <input type="password" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           required 
                                           class="form-control"
                                           placeholder="Confirm password">
                                    <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="action-btn primary">
                                <i class="fas fa-save"></i>
                                Create Admin
                            </button>
                            <a href="{{ route('owner.admins') }}" class="action-btn secondary">
                                <i class="fas fa-times"></i>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
    <style>
        /* Owner specific branding */
        .sidebar-header .logo-icon {
            color: #fbbf24;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .breadcrumb a {
            color: #6b7280;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .breadcrumb a:hover {
            color: #dc2626;
        }

        .breadcrumb .separator {
            color: #d1d5db;
        }

        .breadcrumb .current {
            color: #dc2626;
            font-weight: 500;
        }

        .admin-form {
            max-width: none;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label {
            font-weight: 500;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group label i {
            color: #dc2626;
            width: 1rem;
        }

        .form-control {
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: border-color 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .password-input {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            padding: 0.25rem;
        }

        .toggle-password:hover {
            color: #374151;
        }

        .form-help {
            color: #6b7280;
            font-size: 0.75rem;
        }

        .error-message {
            color: #dc2626;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                justify-content: stretch;
            }
            
            .form-actions .action-btn {
                flex: 1;
            }
        }
    </style>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = input.nextElementSibling;
            const icon = button.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Password strength validation
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const confirmation = document.getElementById('password_confirmation');
            
            // Simple validation
            if (password.length < 8) {
                this.style.borderColor = '#dc2626';
            } else {
                this.style.borderColor = '#10b981';
            }
            
            // Check confirmation match
            if (confirmation.value && confirmation.value !== password) {
                confirmation.style.borderColor = '#dc2626';
            } else if (confirmation.value) {
                confirmation.style.borderColor = '#10b981';
            }
        });

        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmation = this.value;
            
            if (confirmation !== password) {
                this.style.borderColor = '#dc2626';
            } else {
                this.style.borderColor = '#10b981';
            }
        });
    </script>
</body>

</html>
                    <i class="fas fa-info-circle"></i>
                    <h3>Admin Permissions</h3>
                </div>
                <div class="info-content">
                    <p>New admins will have access to:</p>
                    <ul>
                        <li><i class="fas fa-check"></i> Room management</li>
                        <li><i class="fas fa-check"></i> FAQ management</li>
                        <li><i class="fas fa-check"></i> View bookings</li>
                        <li><i class="fas fa-check"></i> Admin dashboard</li>
                    </ul>
                    <p class="note">
                        <i class="fas fa-exclamation-triangle"></i>
                        Admins created by you can only be managed by you.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const button = field.nextElementSibling;
            const icon = button.querySelector('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return;
            }
            
            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long!');
                return;
            }
        });
    </script>
</body>
</html>
