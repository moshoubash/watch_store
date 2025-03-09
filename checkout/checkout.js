document.addEventListener('DOMContentLoaded', function() {
    // Apply Coupon Button Handler
    const applyCouponBtn = document.getElementById('applyCoupon');
    const couponMessageDiv = document.getElementById('coupon-message');
    
    if (applyCouponBtn) {
        applyCouponBtn.addEventListener('click', function() {
            const couponCode = document.getElementById('couponCode').value.trim();
            
            if (!couponCode) {
                showCouponMessage('Please enter a discount code', 'error');
                return;
            }
            
            // Show loading indication
            showCouponMessage('Verifying coupon...', 'info');
            
            fetch('verify-coupon.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ coupon_code: couponCode })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.valid) {
                    // Update the displayed total
                    const totalElement = document.getElementById('total-amount');
                    totalElement.textContent = data.new_total.toFixed(2) + ' $';
                    
                    // Add or update the discount row in the order summary
                    let discountRow = document.querySelector('.order-row.coupon-discount');
                    if (!discountRow) {
                        discountRow = document.createElement('div');
                        discountRow.className = 'order-row coupon-discount';
                        
                        const orderTotals = document.querySelector('.order-totals');
                        const totalRow = document.querySelector('.total-row');
                        orderTotals.insertBefore(discountRow, totalRow);
                    }
                    
                    discountRow.innerHTML = `
                        <div>Coupon Discount <span class="discount-tag">${couponCode}</span></div>
                        <div>-${data.discount_amount.toFixed(2)} $</div>
                    `;
                    
                    // Add a hidden input to submit with the form
                    let hiddenInput = document.getElementById('hidden-coupon-code');
                    if (!hiddenInput) {
                        hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.id = 'hidden-coupon-code';
                        hiddenInput.name = 'coupon_code';
                        document.querySelector('form').appendChild(hiddenInput);
                    }
                    hiddenInput.value = couponCode;
                    
                    showCouponMessage('Discount applied successfully!', 'success');
                } else {
                    showCouponMessage(data.message || 'Discount code is invalid or expired', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showCouponMessage('An error occurred while validating the coupon: ' + error.message, 'error');
            });
        });
    }
    
    // Helper function to show coupon messages
    function showCouponMessage(message, type) {
        if (!couponMessageDiv) return;
        
        couponMessageDiv.textContent = message;
        couponMessageDiv.className = 'coupon-message ' + type;
        
        // Auto-hide success messages after 5 seconds
        if (type === 'success') {
            setTimeout(() => {
                couponMessageDiv.textContent = '';
                couponMessageDiv.className = 'coupon-message';
            }, 5000);
        }
    }
});