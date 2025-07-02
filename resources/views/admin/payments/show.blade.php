<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payment Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/admin.css'])
    <style>
        .payment-details-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .payment-proof-section {
            text-align: center;
        }
        
        .payment-proof-large {
            max-width: 100%;
            max-height: 500px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .payment-proof-large:hover {
            transform: scale(1.02);
        }
        
        .detail-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #555;
        }
        
        .detail-value {
            color: #333;
            font-weight: 500;
        }
        
        .status-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .status-pending { background: #fff3cd; color: #856404; }
        .status-verified { background: #d4edda; color: #155724; }
        .status-rejected { background: #f8d7da; color: #721c24; }
        
        .verification-form {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            margin-top: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        
        .form-textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            resize: vertical;
            min-height: 100px;
        }
        
        .verification-buttons {
            display: flex;
            gap: 15px;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.9);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal.active {
            display: flex;
        }
        
        .modal img {
            max-width: 95%;
            max-height: 95%;
            border-radius: 12px;
        }
        
        @media (max-width: 768px) {
            .payment-details-container {
                grid-template-columns: 1fr;
            }
        }
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
                <h1><i class="fas fa-receipt"></i> Payment Details</h1>
                <p>Review payment proof and booking information</p>
            </header>

            <div class="payment-details-container">
                <!-- Payment Proof Section -->
                <div class="detail-section payment-proof-section">
                    <h3><i class="fas fa-image"></i> Payment Proof</h3>
                    @if($payment->payment_proof)
                        <div style="margin: 20px 0;">
                            <img src="{{ asset('storage/' . $payment->payment_proof) }}" 
                                 alt="Payment Proof" 
                                 class="payment-proof-large"
                                 onclick="showModal('{{ asset('storage/' . $payment->payment_proof) }}')">
                        </div>
                        <p><small><i class="fas fa-info-circle"></i> Click image to view full size</small></p>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-image" style="font-size: 48px; color: #ccc;"></i>
                            <p>No payment proof uploaded</p>
                        </div>
                    @endif
                </div>

                <!-- Payment Information -->
                <div class="detail-section">
                    <h3><i class="fas fa-info-circle"></i> Payment Information</h3>
                    
                    <div class="detail-row">
                        <span class="detail-label">Payment ID:</span>
                        <span class="detail-value">#{{ $payment->id }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Booking Code:</span>
                        <span class="detail-value">{{ $payment->booking->booking_code }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Payment Method:</span>
                        <span class="detail-value">
                            @if($payment->payment_method == 'qris')
                                <i class="fas fa-qrcode"></i> QRIS
                            @else
                                <i class="fas fa-university"></i> Bank Transfer
                            @endif
                        </span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Amount:</span>
                        <span class="detail-value" style="font-size: 18px; font-weight: bold; color: #dc3545;">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Status:</span>
                        <span class="status-badge status-{{ $payment->status }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Submitted:</span>
                        <span class="detail-value">{{ $payment->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    
                    @if($payment->verified_at)
                    <div class="detail-row">
                        <span class="detail-label">Verified:</span>
                        <span class="detail-value">{{ $payment->verified_at->format('d M Y, H:i') }}</span>
                    </div>
                    @endif
                    
                    @if($payment->verifiedBy)
                    <div class="detail-row">
                        <span class="detail-label">Verified By:</span>
                        <span class="detail-value">{{ $payment->verifiedBy->name }}</span>
                    </div>
                    @endif
                    
                    @if($payment->admin_notes)
                    <div class="detail-row" style="flex-direction: column; align-items: flex-start;">
                        <span class="detail-label">Admin Notes:</span>
                        <span class="detail-value" style="margin-top: 8px; padding: 12px; background: #f8f9fa; border-radius: 6px; width: 100%;">
                            {{ $payment->admin_notes }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Booking Details -->
            <div class="detail-section">
                <h3><i class="fas fa-bed"></i> Booking Details</h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div>
                        <div class="detail-row">
                            <span class="detail-label">Customer:</span>
                            <span class="detail-value">{{ $payment->booking->user->name }}</span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Email:</span>
                            <span class="detail-value">{{ $payment->booking->user->email }}</span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Room:</span>
                            <span class="detail-value">{{ $payment->booking->room->name }}</span>
                        </div>
                    </div>
                    
                    <div>
                        <div class="detail-row">
                            <span class="detail-label">Check-in:</span>
                            <span class="detail-value">{{ \Carbon\Carbon::parse($payment->booking->check_in)->format('d M Y') }}</span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Check-out:</span>
                            <span class="detail-value">{{ \Carbon\Carbon::parse($payment->booking->check_out)->format('d M Y') }}</span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Guests:</span>
                            <span class="detail-value">{{ $payment->booking->guests }} person(s)</span>
                        </div>
                        
                        @if($payment->booking->extra_bed)
                        <div class="detail-row">
                            <span class="detail-label">Extra Bed:</span>
                            <span class="detail-value"><i class="fas fa-check text-success"></i> Yes</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Verification Form -->
            @if($payment->status === 'pending')
            <div class="verification-form">
                <h3><i class="fas fa-gavel"></i> Verify Payment</h3>
                <p>Review the payment proof above and decide whether to verify or reject this payment.</p>
                
                <form method="POST" action="{{ route('admin.payments.verify', $payment->id) }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="admin_notes" class="form-label">
                            <i class="fas fa-comment"></i> Admin Notes (Optional)
                        </label>
                        <textarea name="admin_notes" id="admin_notes" class="form-textarea" 
                                  placeholder="Add any notes about this verification decision..."></textarea>
                    </div>
                    
                    <div class="verification-buttons">
                        <button type="submit" name="action" value="verify" class="action-btn success" 
                                onclick="return confirm('Verify this payment? The booking will be confirmed and customer will be notified.')">
                            <i class="fas fa-check"></i>
                            Verify Payment
                        </button>
                        
                        <button type="submit" name="action" value="reject" class="action-btn danger" 
                                onclick="return confirm('Reject this payment? Customer will need to submit new payment proof.')">
                            <i class="fas fa-times"></i>
                            Reject Payment
                        </button>
                    </div>
                </form>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="dashboard-card">
                <div class="card-content">
                    <div class="action-buttons">
                        <a href="{{ route('admin.payments.index') }}" class="action-btn secondary">
                            <i class="fas fa-arrow-left"></i>
                            Back to Payments
                        </a>
                        
                        <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}" class="action-btn warning">
                            <i class="fas fa-clock"></i>
                            View Pending Payments
                        </a>
                        
                        <a href="mailto:{{ $payment->booking->user->email }}?subject=Regarding your booking {{ $payment->booking->booking_code }}" 
                           class="action-btn info">
                            <i class="fas fa-envelope"></i>
                            Contact Customer
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
        <div class="alert alert-success" style="position: fixed; top: 20px; right: 20px; z-index: 1001;">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error" style="position: fixed; top: 20px; right: 20px; z-index: 1001;">
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

        // Auto hide alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.display = 'none';
            });
        }, 5000);
    </script>
</body>
</html>
