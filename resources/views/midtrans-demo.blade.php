<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midtrans Integration Demo - Bayang Brothers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #dc2626;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #dc2626;
            font-size: 2.5rem;
            margin: 0;
        }
        
        .header p {
            color: #666;
            font-size: 1.1rem;
            margin-top: 10px;
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        
        .feature-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            border-color: #dc2626;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(220, 38, 38, 0.1);
        }
        
        .feature-card i {
            font-size: 3rem;
            color: #dc2626;
            margin-bottom: 15px;
        }
        
        .feature-card h3 {
            color: #333;
            font-size: 1.3rem;
            margin-bottom: 10px;
        }
        
        .feature-card p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.5;
        }
        
        .payment-methods {
            background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%);
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
        }
        
        .payment-methods h2 {
            text-align: center;
            color: #2d3436;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }
        
        .methods-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }
        
        .method-item {
            background: white;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .method-item img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            margin-bottom: 10px;
        }
        
        .method-item span {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: #333;
        }
        
        .integration-steps {
            background: #e3f2fd;
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
        }
        
        .integration-steps h2 {
            color: #1565c0;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }
        
        .steps-list {
            list-style: none;
            padding: 0;
        }
        
        .steps-list li {
            background: white;
            margin: 10px 0;
            padding: 15px 20px;
            border-radius: 8px;
            border-left: 4px solid #1565c0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .steps-list li strong {
            color: #1565c0;
        }
        
        .cta-section {
            text-align: center;
            margin-top: 40px;
            padding: 30px;
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            border-radius: 15px;
            color: white;
        }
        
        .cta-section h2 {
            margin-bottom: 15px;
            font-size: 2rem;
        }
        
        .cta-section p {
            font-size: 1.1rem;
            margin-bottom: 25px;
            opacity: 0.9;
        }
        
        .demo-button {
            display: inline-block;
            background: white;
            color: #0984e3;
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .demo-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }
        
        .security-badges {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 30px 0;
            flex-wrap: wrap;
        }
        
        .security-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f1f3f4;
            padding: 10px 15px;
            border-radius: 25px;
            font-size: 0.9rem;
            color: #333;
        }
        
        .security-badge i {
            color: #4caf50;
        }
        
        @media (max-width: 768px) {
            .container {
                margin: 10px;
                padding: 20px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .feature-grid {
                grid-template-columns: 1fr;
            }
            
            .methods-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-credit-card"></i> Midtrans Integration</h1>
            <p>Payment Gateway Integration untuk Bayang Brothers Booking System</p>
        </div>

        <div class="feature-grid">
            <div class="feature-card">
                <i class="fas fa-shield-alt"></i>
                <h3>Secure Payment</h3>
                <p>Pembayaran aman dengan enkripsi SSL dan 3D Secure authentication untuk credit card</p>
            </div>
            
            <div class="feature-card">
                <i class="fas fa-mobile-alt"></i>
                <h3>Multi-Platform</h3>
                <p>Support pembayaran dari desktop dan mobile dengan responsive design</p>
            </div>
            
            <div class="feature-card">
                <i class="fas fa-clock"></i>
                <h3>Real-time</h3>
                <p>Update status pembayaran secara real-time melalui webhook notification</p>
            </div>
            
            <div class="feature-card">
                <i class="fas fa-chart-line"></i>
                <h3>Analytics</h3>
                <p>Monitoring dan tracking pembayaran lengkap dengan logging system</p>
            </div>
        </div>

        <div class="payment-methods">
            <h2><i class="fas fa-wallet"></i> Metode Pembayaran Tersedia</h2>
            <div class="methods-grid">
                <div class="method-item">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA">
                    <span>BCA</span>
                </div>
                <div class="method-item">
                    <img src="https://upload.wikimedia.org/wikipedia/en/2/27/BNI_logo.svg" alt="BNI">
                    <span>BNI</span>
                </div>
                <div class="method-item">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2e/BRI_2020.svg" alt="BRI">
                    <span>BRI</span>
                </div>
                <div class="method-item">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg" alt="Mandiri">
                    <span>Mandiri</span>
                </div>
                <div class="method-item">
                    <img src="https://logos-world.net/wp-content/uploads/2020/09/Gojek-Logo.png" alt="GoPay">
                    <span>GoPay</span>
                </div>
                <div class="method-item">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/f/fe/Shopee.svg" alt="ShopeePay">
                    <span>ShopeePay</span>
                </div>
                <div class="method-item">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa">
                    <span>Visa</span>
                </div>
                <div class="method-item">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="MasterCard">
                    <span>MasterCard</span>
                </div>
            </div>
        </div>

        <div class="security-badges">
            <div class="security-badge">
                <i class="fas fa-lock"></i>
                <span>SSL Encrypted</span>
            </div>
            <div class="security-badge">
                <i class="fas fa-shield-check"></i>
                <span>PCI DSS Compliant</span>
            </div>
            <div class="security-badge">
                <i class="fas fa-user-shield"></i>
                <span>3D Secure</span>
            </div>
            <div class="security-badge">
                <i class="fas fa-eye"></i>
                <span>Fraud Detection</span>
            </div>
        </div>

        <div class="integration-steps">
            <h2><i class="fas fa-cogs"></i> Komponen Integrasi</h2>
            <ol class="steps-list">
                <li><strong>PaymentController:</strong> Controller untuk handle payment API dan callbacks</li>
                <li><strong>Midtrans Snap:</strong> JavaScript integration untuk payment popup</li>
                <li><strong>Database Migration:</strong> Kolom tambahan untuk tracking payment</li>
                <li><strong>Webhook Handler:</strong> Automatic status update dari Midtrans</li>
                <li><strong>Payment UI:</strong> Interface yang user-friendly untuk pilih metode pembayaran</li>
                <li><strong>Security Features:</strong> CSRF protection, authentication, dan validation</li>
            </ol>
        </div>

        <div class="cta-section">
            <h2>Ready to Use!</h2>
            <p>Integrasi Midtrans telah siap digunakan. Pastikan konfigurasi API keys sudah benar di file .env</p>
            <a href="/payment/1" class="demo-button">
                <i class="fas fa-rocket"></i> Test Payment Integration
            </a>
        </div>

        <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px; text-align: center;">
            <h3><i class="fas fa-file-alt"></i> Documentation</h3>
            <p>Lihat file <code>MIDTRANS_INTEGRATION.md</code> untuk dokumentasi lengkap setup dan konfigurasi.</p>
            <div style="margin-top: 15px;">
                <strong>Files Created/Modified:</strong><br>
                <code>PaymentController.php</code> • 
                <code>midtrans-payment.js</code> • 
                <code>midtrans-payment.css</code> • 
                <code>payment.blade.php</code> • 
                <code>payment-pending.blade.php</code>
            </div>
        </div>
    </div>
</body>
</html>
