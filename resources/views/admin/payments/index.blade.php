<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payment Verification - Admin Panel - Bayang Brothers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/admin.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <style>
        .payment-proof-img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .payment-proof-img:hover {
            transform: scale(1.05);
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .modal.active {
            display: flex;
        }
        .modal img {
            max-width: 90%;
            max-height: 90%;
            border-radius: 8px;
        }
        .filter-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        .filter-item label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-verified { background: #d4edda; color: #155724; }
        .status-rejected { background: #f8d7da; color: #721c24; }
    </style>
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
                <a href="{{ route('admin.rooms.index') }}" class="nav-item">
                    <i class="fas fa-bed"></i>
                    Manage Rooms
                </a>
                <a href="{{ route('admin.faqs.index') }}" class="nav-item">
                    <i class="fas fa-question-circle"></i>
                    Manage FAQ
                </a>
                <a href="{{ route('admin.payments.index') }}" class="nav-item active">
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
                <h1><i class="fas fa-credit-card"></i> Payment Verification</h1>
                <p>Verify customer payment proofs and manage booking confirmations</p>
            </header>

            <!-- Filters -->
            <div class="filter-form">
                <form method="GET" action="{{ route('admin.payments.index') }}">
                    <div class="filter-grid">
                        <div class="filter-item">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        
                        <div class="filter-item">
                            <label for="payment_method">Payment Method</label>
                            <select name="payment_method" id="payment_method" class="form-select">
                                <option value="">All Methods</option>
                                <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                                <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="filter-actions">
                        <button type="submit" class="action-btn primary">
                            <i class="fas fa-filter"></i>
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.payments.index') }}" class="action-btn outline">
                            <i class="fas fa-undo"></i>
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Payment Statistics -->
            <div class="dashboard-grid" style="margin-bottom: 30px;">
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-clock"></i>
                        <h3>Pending</h3>
                    </div>
                    <div class="card-content">
                        @php
                            $pendingCount = $payments->where('status', 'pending')->count();
                        @endphp
                        <div class="stat-number">{{ $pendingCount }}</div>
                        <div class="stat-label">Need Verification</div>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-check-circle"></i>
                        <h3>Verified</h3>
                    </div>
                    <div class="card-content">
                        @php
                            $verifiedCount = $payments->where('status', 'verified')->count();
                        @endphp
                        <div class="stat-number">{{ $verifiedCount }}</div>
                        <div class="stat-label">Approved Payments</div>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-times-circle"></i>
                        <h3>Rejected</h3>
                    </div>
                    <div class="card-content">
                        @php
                            $rejectedCount = $payments->where('status', 'rejected')->count();
                        @endphp
                        <div class="stat-number">{{ $rejectedCount }}</div>
                        <div class="stat-label">Rejected Payments</div>
                    </div>
                </div>
            </div>

            <!-- Payments Table -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-list"></i>
                    <h3>Payment Submissions</h3>
                    <div class="card-actions">
                        <span class="badge">{{ $payments->total() ?? 0 }} total</span>
                    </div>
                </div>
                <div class="card-content">
                    @if($payments->count() > 0)
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Booking Code</th>
                                        <th>Customer</th>
                                        <th>Room</th>
                                        <th>Amount</th>
                                        <th>Payment Method</th>
                                        <th>Proof</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                    <tr>
                                        <td>
                                            <div class="booking-code">{{ $payment->booking->booking_code }}</div>
                                        </td>
                                        <td>
                                            <div class="customer-info">
                                                <div class="customer-name">{{ $payment->booking->user->name }}</div>
                                                <div class="customer-email">{{ $payment->booking->user->email }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="room-name">{{ $payment->booking->room->name }}</div>
                                        </td>
                                        <td>
                                            <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong>
                                        </td>
                                        <td>
                                            <span class="method-badge">
                                                @if($payment->payment_method == 'qris')
                                                    <i class="fas fa-qrcode"></i> QRIS
                                                @else
                                                    <i class="fas fa-university"></i> Bank Transfer
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            @if($payment->payment_proof)
                                                <img src="{{ asset('storage/' . $payment->payment_proof) }}" 
                                                     alt="Payment Proof" 
                                                     class="payment-proof-img"
                                                     onclick="showModal('{{ asset('storage/' . $payment->payment_proof) }}')">
                                            @else
                                                <span class="text-muted">No proof</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ $payment->status }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $payment->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td>
                                            <div class="action-buttons-inline">
                                                <a href="{{ route('admin.payments.show', $payment->id) }}" 
                                                   class="btn-small btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if($payment->status === 'pending')
                                                    <form method="POST" action="{{ route('admin.payments.verify', $payment->id) }}" style="display: inline;">
                                                        @csrf
                                                        <input type="hidden" name="action" value="verify">
                                                        <button type="submit" class="btn-small btn-success" title="Verify Payment"
                                                                onclick="return confirm('Verify this payment? Booking will be confirmed.')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    <form method="POST" action="{{ route('admin.payments.verify', $payment->id) }}" style="display: inline;">
                                                        @csrf
                                                        <input type="hidden" name="action" value="reject">
                                                        <button type="submit" class="btn-small btn-danger" title="Reject Payment"
                                                                onclick="return confirm('Reject this payment? Customer will need to resubmit.')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($payments->hasPages())
                            <div class="pagination-wrapper">
                                {{ $payments->links() }}
                            </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <i class="fas fa-credit-card"></i>
                            <h4>No Payment Submissions</h4>
                            <p>
                                @if(request()->hasAny(['status', 'payment_method']))
                                    No payments match your current filters.
                                @else
                                    No payment submissions yet. They will appear here when customers upload payment proofs.
                                @endif
                            </p>
                        </div>
                    @endif
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
                        <a href="{{ route('admin.dashboard') }}" class="action-btn secondary">
                            <i class="fas fa-tachometer-alt"></i>
                            Back to Dashboard
                        </a>
                        <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}" class="action-btn warning">
                            <i class="fas fa-clock"></i>
                            View Pending Only
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="modal" onclick="hideModal()">
        <img id="modalImage" src="" alt="Payment Proof">
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
        function showModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').classList.add('active');
        }

        function hideModal() {
            document.getElementById('imageModal').classList.remove('active');
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideModal();
            }
        });
    </script>
</body>
</html>
