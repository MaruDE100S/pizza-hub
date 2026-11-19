document.addEventListener('DOMContentLoaded', () => {
    console.log('PizzaHub JS initializationed.');

    // Cart
    const removeButtons = document.querySelectorAll('.btn-remove');

    removeButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            const confirmed = confirm('Are you sure you want to remove this pizza from your cart?');

            if (!confirmed) {
                event.preventDefault();
            }
        });
    });

    // Register
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const submitBtn = document.querySelector('form button[type="submit"]');

    if (passwordInput && confirmInput) {

        const errorMsg = document.createElement('small');
        errorMsg.style.color = '#ef4444';
        errorMsg.style.fontWeight = 'bold';
        errorMsg.style.display = 'none';
        confirmInput.parentNode.insertBefore(errorMsg, confirmInput.nextSibling);

        function validatePasswords() {
            const passVal = passwordInput.value;
            const confirmVal = confirmInput.value;

            if (confirmVal.length > 0 && passVal !== confirmVal) {
                errorMsg.textContent = 'Passwords don\'t match!';
                errorMsg.style.display = 'block';
                confirmInput.style.borderColor = '#ef4444';
                if (submitBtn) submitBtn.disabled = true;
            } else {
                errorMsg.style.display = 'none';
                confirmInput.style.borderColor = '';
                if (submitBtn) submitBtn.disabled = false;
            }
        }

        passwordInput.addEventListener('input', validatePasswords);
        confirmInput.addEventListener('input', validatePasswords);
    }

    // Menu <-> Cart
    const addToCartButtons = document.querySelectorAll('a[href*="page=Cart&add="]');
    const cartCountBadge = document.getElementById('cart-count');

    addToCartButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault();

            const originalUrl = button.getAttribute('href');
            const originalText = button.textContent;

            const ajaxUrl = originalUrl + '&ajax=1';

            button.textContent = 'Adding...';
            button.style.pointerEvents = 'none';

            fetch(ajaxUrl)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (cartCountBadge) {
                            cartCountBadge.textContent = data.totalItems;
                        }

                        button.textContent = 'ADDED!';
                        button.style.backgroundColor = '#22c55e';

                        setTimeout(() => {
                            button.textContent = originalText;
                            button.style.backgroundColor = '';
                            button.style.pointerEvents = 'auto';
                        }, 800);
                    }
                })
                .catch(err => {
                    console.error('Cart Error:', err);
                    button.textContent = originalText;
                    button.style.pointerEvents = 'auto';
                });
        });
    });
});
