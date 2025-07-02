<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payments Management - Owner Panel</title>
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
                <a href="{{ route('owner.payments') }}" class="nav-item active">
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

        <!-- Main Content -->
        <main class="main-content">
            <header class="content-header">
                <h1>Payments Management</h1>
                <p>Monitor and track all payment transactions</p>
            </header>

            <!-- Filters -->
            <div class="filter-section">
                <form method="GET" action="{{ route('owner.payments') }}" class="filter-form">
                    <div class="filter-group">
                        <div class="form-group">
                            <label for="search">Search</label>
                            <input type="text" id="search" name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Booking code, user name, email...">
                        </div>
                        
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="payment_method">Payment Method</label>
                            <select id="payment_method" name="payment_method">
                                <option value="">All Methods</option>
                                <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                                <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="date_from">Date From</label>
                            <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}">
                        </div>

                        <div class="form-group">
                            <label for="date_to">Date To</label>
                            <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                                Filter
                            </button>
                            <a href="{{ route('owner.payments') }}" class="btn btn-secondary">
                                <i class="fas fa-refresh"></i>
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Payments Table -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Booking Code</th>
                            <th>Customer</th>
                            <th>Room</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>
                                <span class="booking-code">{{ $payment->booking->booking_code }}</span>
                            </td>
                            <td>
                                <div class="customer-info">
                                    <strong>{{ $payment->booking->user->name }}</strong>
                                    <small>{{ $payment->booking->user->email }}</small>
                                </div>
                            </td>
                            <td>{{ $payment->booking->room->name }}</td>
                            <td>
                                <span class="amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                <span class="payment-method {{ $payment->payment_method }}">
                                    @if($payment->payment_method == 'qris')
                                        <i class="fas fa-qrcode"></i> QRIS
                                    @else
                                        <i class="fas fa-university"></i> Bank Transfer
                                    @endif
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $payment->status }}">
                                    @switch($payment->status)
                                        @case('pending')
                                            <i class="fas fa-clock"></i> Pending
                                            @break
                                        @case('verified')
                                            <i class="fas fa-check-circle"></i> Verified
                                            @break
                                        @case('rejected')
                                            <i class="fas fa-times-circle"></i> Rejected
                                            @break
                                    @endswitch
                                </span>
                            </td>
                            <td>
                                <div class="date-info">
                                    <strong>{{ $payment->created_at->format('d M Y') }}</strong>
                                    <small>{{ $payment->created_at->format('H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('owner.payments.show', $payment->id) }}" 
                                       class="btn btn-sm btn-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-credit-card"></i>
                                    <h3>No Payments Found</h3>
                                    <p>No payment transactions match your current filters.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($payments->hasPages())
            <div class="pagination-wrapper">
                {{ $payments->appends(request()->query())->links() }}
            </div>
            @endif
        </main>
    </div>

    <style>
        .payment-method.qris {
            color: #7C3AED;
            background-color: #F3F4F6;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
        }

        .payment-method.bank_transfer {
            color: #059669;
            background-color: #F3F4F6;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
        }

        .amount {
            font-weight: 600;
            color: #059669;
        }

        .customer-info strong {
            display: block;
        }

        .customer-info small {
            color: #6B7280;
        }

        .date-info strong {
            display: block;
        }

        .date-info small {
            color: #6B7280;
        }

        .booking-code {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            color: #374151;
        }
    </style>
</body>

</html>
