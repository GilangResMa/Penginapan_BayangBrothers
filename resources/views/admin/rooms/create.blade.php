<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add New Room</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/admin.css'])
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
                <a href="{{ route('admin.dashboard') }}" class="nav-item">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.rooms.index') }}" class="nav-item active">
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
                <h1>Add New Room</h1>
                <p>Create a new room with pricing details</p>
            </header>

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul style="margin: 0; padding-left: 1rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-container">
                <form method="POST" action="{{ route('admin.rooms.store') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name" class="form-label">Room Name</label>
                        <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-input form-textarea" required>{{ old('description') }}</textarea>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        <div class="form-group">
                            <label for="price_weekday" class="form-label">Weekday Price (Rp)</label>
                            <input type="number" id="price_weekday" name="price_weekday" class="form-input" 
                                   value="{{ old('price_weekday') }}" min="0" step="1000" required>
                        </div>

                        <div class="form-group">
                            <label for="price_weekend" class="form-label">Weekend Price (Rp)</label>
                            <input type="number" id="price_weekend" name="price_weekend" class="form-input" 
                                   value="{{ old('price_weekend') }}" min="0" step="1000" required>
                        </div>

                        <div class="form-group">
                            <label for="extra_bed_price" class="form-label">Extra Bed Price (Rp)</label>
                            <input type="number" id="extra_bed_price" name="extra_bed_price" class="form-input" 
                                   value="{{ old('extra_bed_price') }}" min="0" step="1000" required>
                        </div>

                        <div class="form-group">
                            <label for="max_guests" class="form-label">Max Guests</label>
                            <input type="number" id="max_guests" name="max_guests" class="form-input" 
                                   value="{{ old('max_guests', 2) }}" min="1" max="10" required>
                        </div>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Create Room
                        </button>
                        <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Back to Rooms
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>

</html>
