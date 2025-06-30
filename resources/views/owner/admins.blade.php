<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Management - Bayang Brothers</title>
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
                <h1>Admin Management</h1>
                <p>Manage administrators and their permissions</p>
            </header>
            <!-- Success Message -->
            @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-user-plus"></i>
                    <h3>Quick Actions</h3>
                </div>
                <div class="card-content">
                    <div class="action-buttons">
                        <a href="{{ route('owner.admin.create') }}" class="action-btn primary">
                            <i class="fas fa-plus"></i>
                            Add New Admin
                        </a>
                        <a href="{{ route('owner.dashboard') }}" class="action-btn secondary">
                            <i class="fas fa-chart-line"></i>
                            View Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Admins Table -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-users-cog"></i>
                    <h3>All Admins ({{ $admins->total() }})</h3>
                </div>
                <div class="card-content">
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
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
                                        <div class="table-actions">
                                            <a href="{{ route('owner.admin.edit', $admin->id) }}" class="action-btn primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </a>
                                            <button class="action-btn danger btn-sm" onclick="confirmDelete({{ $admin->id }}, '{{ $admin->name }}')">
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
                                            <a href="{{ route('owner.admin.create') }}" class="action-btn primary">
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
            </div>
        </main>
    </div>
    
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
                <button class="action-btn secondary" onclick="closeDeleteModal()">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn danger">Delete Admin</button>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Owner specific branding */
        .sidebar-header .logo-icon {
            color: #fbbf24;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .admin-info i {
            color: #6366f1;
        }

        .table-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .status {
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 0;
            border-radius: 0.5rem;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            color: #1f2937;
        }

        .close {
            color: #6b7280;
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #374151;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .action-btn.danger {
            background: #dc2626;
            color: white;
        }

        .action-btn.danger:hover {
            background: #b91c1c;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6b7280;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #d1d5db;
        }

        .pagination-wrapper {
            padding: 1rem;
            border-top: 1px solid #e5e7eb;
        }
    </style>

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
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>

</html>
            const modal = document.getElementById('deleteModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
