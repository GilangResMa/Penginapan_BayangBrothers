<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management - Bayang Brothers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/admin.css'])
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-crown"></i>
                <span>Owner Panel</span>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <a href="{{ route('owner.dashboard') }}" class="nav-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('owner.bookings') }}" class="nav-item">
                <i class="fas fa-calendar-check"></i>
                <span>Bookings</span>
            </a>
            <a href="{{ route('owner.revenue') }}" class="nav-item">
                <i class="fas fa-chart-line"></i>
                <span>Revenue</span>
            </a>
            <a href="{{ route('owner.admins') }}" class="nav-item active">
                <i class="fas fa-users-cog"></i>
                <span>Manage Admins</span>
            </a>
            <a href="{{ route('homepage') }}" class="nav-item">
                <i class="fas fa-home"></i>
                <span>Back to Site</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="content-header">
            <h1>Admin Management</h1>
            <div class="header-actions">
                <a href="{{ route('owner.admin.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Add New Admin
                </a>
            </div>
        </header>

        <!-- Success Message -->
        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
        @endif

        <!-- Admins Table -->
        <div class="table-card">
            <div class="card-header">
                <h3>All Admins ({{ $admins->total() }})</h3>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($admins as $admin)
                        <tr>
                            <td>
                                <div class="admin-info">
                                    <i class="fas fa-user-shield"></i>
                                    <strong>{{ $admin->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $admin->email }}</td>
                            <td>{{ $admin->phone ?? '-' }}</td>
                            <td>
                                <span class="status status-{{ $admin->status ? 'active' : 'inactive' }}">
                                    {{ $admin->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $admin->created_at ? $admin->created_at->format('d M Y') : '-' }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('owner.admin.edit', $admin->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </a>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $admin->id }}, '{{ $admin->name }}')">
                                        <i class="fas fa-trash"></i>
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-users-slash"></i>
                                    <p>No admins created yet</p>
                                    <a href="{{ route('owner.admin.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i>
                                        Create First Admin
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($admins->hasPages())
            <div class="pagination-wrapper">
                {{ $admins->links() }}
            </div>
            @endif
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirm Delete</h3>
                <span class="close" onclick="closeDeleteModal()">&times;</span>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete admin "<span id="adminName"></span>"?</p>
                <p><strong>This action cannot be undone.</strong></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Admin</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(adminId, adminName) {
            document.getElementById('adminName').textContent = adminName;
            document.getElementById('deleteForm').action = `/owner/admin/${adminId}/delete`;
            document.getElementById('deleteModal').style.display = 'block';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
