<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Dashboard - Bayang Brothers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/admin.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
</head>

<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-shield-alt logo-icon"></i>
                <h2>Admin Panel</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}" class="nav-item active">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.rooms.index') }}" class="nav-item">
                    <i class="fas fa-bed"></i>
                    Manage Rooms
                </a>
                <a href="{{ route('admin.faqs.index') }}" class="nav-item">
                    <i class="fas fa-question-circle"></i>
                    Manage FAQ
                </a>
                <a href="{{ route('admin.payments.index') }}" class="nav-item">
                    <i class="fas fa-credit-card"></i>
                    Payment Verification
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
                <h1>Admin Dashboard</h1>
                <p>Welcome to Bayang Brothers Admin Panel</p>
            </header>

            <div class="dashboard-grid">
                <!-- Room Stats -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-bed"></i>
                        <h3>Rooms</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">{{ $rooms->count() }}</div>
                        <div class="stat-label">Total Rooms</div>
                        <a href="{{ route('admin.rooms.index') }}" class="card-action">
                            Manage Rooms <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- FAQ Stats -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-question-circle"></i>
                        <h3>FAQ</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">{{ $faqs->count() }}</div>
                        <div class="stat-label">Total FAQ</div>
                        <a href="{{ route('admin.faqs.index') }}" class="card-action">
                            Manage FAQ <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Payment Stats -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-credit-card"></i>
                        <h3>Payments</h3>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">{{ $pendingPayments }}</div>
                        <div class="stat-label">Pending Verification</div>
                        <a href="{{ route('admin.payments.index') }}" class="card-action">
                            Verify Payments <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="dashboard-card span-full">
                    <div class="card-header">
                        <i class="fas fa-lightning-bolt"></i>
                        <h3>Quick Actions</h3>
                    </div>
                    <div class="card-content">
                        <div class="action-buttons">
                            <a href="{{ route('admin.rooms.create') }}" class="action-btn primary">
                                <i class="fas fa-plus"></i>
                                Add New Room
                            </a>
                            <a href="{{ route('admin.faqs.create') }}" class="action-btn secondary">
                                <i class="fas fa-plus"></i>
                                Add New FAQ
                            </a>
                            <a href="{{ route('admin.payments.index') }}" class="action-btn warning">
                                <i class="fas fa-credit-card"></i>
                                Verify Payments
                            </a>
                            <a href="{{ route('homepage') }}" class="action-btn tertiary">
                                <i class="fas fa-eye"></i>
                                View Website
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
