/**
 * Minimal JavaScript - Server-side driven, minimal client-side dependencies
 * All form submissions use standard HTML forms (POST/GET)
 * All interactions work without JavaScript
 */

// Simple utility functions
document.addEventListener('DOMContentLoaded', function() {
    // Bootstrap dropdowns work automatically
    // Form validation works with HTML5 validation
    // No heavy dependencies needed
    
    // Optional: Add confirm dialog for destructive actions
    const deleteButtons = document.querySelectorAll('form[data-confirm] button[type="submit"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const form = this.closest('form');
            const message = form.getAttribute('data-confirm') || 'Are you sure?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const closeButton = alert.querySelector('.btn-close');
            if (closeButton) {
                closeButton.click();
            }
        }, 5000);
    });

    // Format numbers as currency (if needed)
    const priceElements = document.querySelectorAll('[data-price]');
    priceElements.forEach(element => {
        const price = parseFloat(element.getAttribute('data-price'));
        if (!isNaN(price)) {
            element.textContent = '$' + price.toFixed(2);
        }
    });
});

// Prevent form double submission
document.addEventListener('submit', function(e) {
    const form = e.target;
    if (form.classList.contains('submitted')) {
        e.preventDefault();
        return false;
    }
    form.classList.add('submitted');
    
    // Re-enable if submission takes more than 3 seconds (error handling)
    setTimeout(() => {
        form.classList.remove('submitted');
    }, 3000);
});
