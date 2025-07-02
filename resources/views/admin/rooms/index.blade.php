<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Manage Rooms</title>
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
                <h1>Manage Rooms</h1>
                <p>Add, edit, and manage hotel rooms</p>
            </header>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div style="margin-bottom: 1.5rem;">
                <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Add New Room
                </a>
            </div>

            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Weekday Price</th>
                            <th>Weekend Price</th>
                            <th>Extra Bed Price</th>
                            <th>Max Guests</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rooms as $room)
                            <tr>
                                <td style="font-weight: 500;">{{ $room->name }}</td>
                                <td
                                    style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $room->description }}
                                </td>
                                <td>Rp {{ number_format($room->price_weekday, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($room->price_weekend, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($room->extra_bed_price, 0, ',', '.') }}</td>
                                <td>{{ $room->max_guests }}</td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-secondary"
                                            style="padding: 0.5rem;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.rooms.destroy', $room->id) }}"
                                            style="display: inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this room?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" style="padding: 0.5rem;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; color: #6b7280; padding: 2rem;">
                                    No rooms found. <a href="{{ route('admin.rooms.create') }}"
                                        style="color: #dc2626;">Add your first room</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>

</html>
