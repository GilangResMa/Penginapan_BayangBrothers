// Midtrans Payment Integration for Bayang Brothers
document.addEventListener('DOMContentLoaded', function() {
    initializePaymentIntegration();
});

function initializePaymentIntegration() {
    const paymentForm = document.querySelector('.payment-form');
    const paymentOptions = document.querySelectorAll('input[name="payment_method"]');
    
    if (!paymentForm) return;

    // Handle payment method changes
    paymentOptions.forEach(option => {
        option.addEventListener('change', function() {
            showPaymentMethodDetails(this.value);
        });
    });

    // Handle form submission
    paymentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        if (!selectedMethod) {
            showAlert('Please select a payment method', 'error');
            return;
        }

        processPayment(selectedMethod.value);
    });
}

function showPaymentMethodDetails(method) {
    const detailsContainer = document.querySelector('.payment-method-details');
    if (!detailsContainer) return;

    let detailsHTML = '';

    switch(method) {
        case 'bank_transfer':
            detailsHTML = `
                <div class="payment-details-card">
                    <h4><i class="fas fa-university"></i> Bank Transfer Details</h4>
                    <div class="bank-options">
                        <div class="bank-option" data-bank="bca">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA" class="bank-logo">
                            <span>BCA Virtual Account</span>
                        </div>
                        <div class="bank-option" data-bank="bni">
                            <img src="https://upload.wikimedia.org/wikipedia/en/2/27/BNI_logo.svg" alt="BNI" class="bank-logo">
                            <span>BNI Virtual Account</span>
                        </div>
                        <div class="bank-option" data-bank="bri">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2e/BRI_2020.svg" alt="BRI" class="bank-logo">
                            <span>BRI Virtual Account</span>
                        </div>
                        <div class="bank-option" data-bank="mandiri">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg" alt="Mandiri" class="bank-logo">
                            <span>Mandiri Bill Payment</span>
                        </div>
                    </div>
                </div>
            `;
            break;
        case 'credit_card':
            detailsHTML = `
                <div class="payment-details-card">
                    <h4><i class="fas fa-credit-card"></i> Credit Card Payment</h4>
                    <p>Secure payment using Visa, MasterCard, or JCB</p>
                    <div class="card-logos">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa" class="card-logo">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="MasterCard" class="card-logo">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/4/40/JCB_logo.svg" alt="JCB" class="card-logo">
                    </div>
                </div>
            `;
            break;
        case 'digital_wallet':
            detailsHTML = `
                <div class="payment-details-card">
                    <h4><i class="fas fa-mobile-alt"></i> Digital Wallet Payment</h4>
                    <div class="wallet-options">
                        <div class="wallet-option" data-wallet="gopay">
                            <img src="https://logos-world.net/wp-content/uploads/2020/09/Gojek-Logo.png" alt="GoPay" class="wallet-logo">
                            <span>GoPay</span>
                        </div>
                        <div class="wallet-option" data-wallet="shopeepay">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/f/fe/Shopee.svg" alt="ShopeePay" class="wallet-logo">
                            <span>ShopeePay</span>
                        </div>
                        <div class="wallet-option" data-wallet="dana">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg" alt="DANA" class="wallet-logo">
                            <span>DANA</span>
                        </div>
                        <div class="wallet-option" data-wallet="ovo">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/e/eb/Logo_ovo_purple.svg" alt="OVO" class="wallet-logo">
                            <span>OVO</span>
                        </div>
                    </div>
                </div>
            `;
            break;
    }

    detailsContainer.innerHTML = detailsHTML;
    addPaymentMethodEventListeners();
}

function addPaymentMethodEventListeners() {
    // Bank option selection
    const bankOptions = document.querySelectorAll('.bank-option');
    bankOptions.forEach(option => {
        option.addEventListener('click', function() {
            bankOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
        });
    });

    // Wallet option selection
    const walletOptions = document.querySelectorAll('.wallet-option');
    walletOptions.forEach(option => {
        option.addEventListener('click', function() {
            walletOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
        });
    });
}

function processPayment(paymentMethod) {
    const submitButton = document.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    // Show loading state
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing Payment...';
    submitButton.disabled = true;

    // Get booking data
    const bookingData = getBookingData();
    
    // Prepare payment data
    const paymentData = {
        booking_id: bookingData.booking_id,
        payment_method: paymentMethod,
        total_amount: bookingData.total_amount,
        customer_details: {
            first_name: bookingData.customer_name,
            email: bookingData.customer_email,
            phone: bookingData.customer_phone
        }
    };

    // Add specific payment method data
    if (paymentMethod === 'bank_transfer') {
        const selectedBank = document.querySelector('.bank-option.selected');
        if (selectedBank) {
            paymentData.bank = selectedBank.dataset.bank;
        }
    } else if (paymentMethod === 'digital_wallet') {
        const selectedWallet = document.querySelector('.wallet-option.selected');
        if (selectedWallet) {
            paymentData.wallet = selectedWallet.dataset.wallet;
        }
    }

    // Send request to create Midtrans transaction
    fetch('/api/payment/create-snap-token', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(paymentData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Open Midtrans Snap payment page
            window.snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    handlePaymentSuccess(result);
                },
                onPending: function(result) {
                    handlePaymentPending(result);
                },
                onError: function(result) {
                    handlePaymentError(result);
                },
                onClose: function() {
                    handlePaymentClose();
                }
            });
        } else {
            throw new Error(data.message || 'Failed to create payment');
        }
    })
    .catch(error => {
        console.error('Payment error:', error);
        showAlert('Payment failed: ' + error.message, 'error');
    })
    .finally(() => {
        // Restore button state
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    });
}

function getBookingData() {
    // Extract booking data from the page
    const bookingCodeElement = document.querySelector('.booking-code');
    const totalAmountElement = document.querySelector('.total-price');
    
    return {
        booking_id: window.bookingId || null, // This should be set in the Blade template
        booking_code: bookingCodeElement ? bookingCodeElement.textContent.trim() : '',
        total_amount: extractAmountFromText(totalAmountElement ? totalAmountElement.textContent : '0'),
        customer_name: window.customerName || '',
        customer_email: window.customerEmail || '',
        customer_phone: window.customerPhone || ''
    };
}

function extractAmountFromText(text) {
    // Extract numeric value from formatted currency text
    return parseInt(text.replace(/[^\d]/g, '')) || 0;
}

function handlePaymentSuccess(result) {
    console.log('Payment success:', result);
    
    showAlert('Payment successful! Your booking has been confirmed.', 'success');
    
    // Redirect to success page
    setTimeout(() => {
        window.location.href = `/payment/success/${window.bookingId}?transaction_id=${result.transaction_id}`;
    }, 2000);
}

function handlePaymentPending(result) {
    console.log('Payment pending:', result);
    
    showAlert('Payment is being processed. Please follow the instructions to complete your payment.', 'info');
    
    // Redirect to pending page or show instructions
    setTimeout(() => {
        window.location.href = `/payment/pending/${window.bookingId}?transaction_id=${result.transaction_id}`;
    }, 3000);
}

function handlePaymentError(result) {
    console.error('Payment error:', result);
    
    showAlert('Payment failed. Please try again or contact customer service.', 'error');
}

function handlePaymentClose() {
    console.log('Payment popup closed by user');
    
    showAlert('Payment cancelled. You can try again anytime.', 'info');
}

function showAlert(message, type = 'info') {
    // Create alert element
    const alertElement = document.createElement('div');
    alertElement.className = `alert alert-${type}`;
    alertElement.innerHTML = `
        <i class="fas fa-${getAlertIcon(type)}"></i>
        <span>${message}</span>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // Insert at the top of the main content
    const mainContent = document.querySelector('.main');
    if (mainContent) {
        mainContent.insertBefore(alertElement, mainContent.firstChild);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertElement.parentElement) {
                alertElement.remove();
            }
        }, 5000);
    }
}

function getAlertIcon(type) {
    const icons = {
        success: 'check-circle',
        error: 'exclamation-circle',
        info: 'info-circle',
        warning: 'exclamation-triangle'
    };
    return icons[type] || 'info-circle';
}

// Initialize Midtrans Snap on page load
window.addEventListener('load', function() {
    // Check if Midtrans Snap is loaded
    if (typeof window.snap === 'undefined') {
        console.error('Midtrans Snap is not loaded');
        showAlert('Payment system is not available. Please refresh the page.', 'error');
    }
});
