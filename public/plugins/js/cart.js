// Cart Management System
class CartManager {
    constructor() {
        this.init();
    }

    init() {
        this.initializeForms();
        this.initializeQuantityInputs();
    }

    initializeForms() {
        // Handle update form submission
        const updateForms = document.querySelectorAll('.update-form');
        updateForms.forEach(form => {
            form.addEventListener('submit', (e) => this.handleUpdate(e));
        });

        // Handle remove form submission
        const removeForms = document.querySelectorAll('.remove-form');
        removeForms.forEach(form => {
            form.addEventListener('submit', (e) => this.handleRemove(e));
        });
    }

    initializeQuantityInputs() {
        const quantityInputs = document.querySelectorAll('.quantity-input');
        quantityInputs.forEach(input => {
            input.addEventListener('change', (e) => {
                const form = input.closest('.update-form');
                if (form && input.value >= 1) {
                    form.dispatchEvent(new Event('submit'));
                } else if (input.value < 1) {
                    input.value = 1;
                    form.dispatchEvent(new Event('submit'));
                }
            });
        });
    }

    async handleUpdate(event) {
        event.preventDefault();
        
        const form = event.currentTarget;
        const cartId = form.dataset.id;
        const quantity = form.querySelector('input[name="quantity"]').value;
        const updateButton = form.querySelector('.btn-update');
        
        // Show loading state
        this.setLoading(updateButton, true);
        
        try {
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('quantity', quantity);
            
            const response = await fetch(`/cart/update/${cartId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Update subtotal
                this.updateSubtotal(cartId, data.subtotal);
                this.showAlert('success', data.message);
                
                // Update cart count in navbar
                if (window.updateCartCount) {
                    window.updateCartCount();
                }
                
                // Reload after 1 second to refresh total
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                this.showAlert('danger', data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('danger', 'Terjadi kesalahan saat memperbarui keranjang');
        } finally {
            this.setLoading(updateButton, false);
        }
    }

    async handleRemove(event) {
        event.preventDefault();
        
        if (!confirm('Yakin ingin menghapus item ini?')) return;
        
        const form = event.currentTarget;
        const cartId = form.dataset.id;
        const removeButton = form.querySelector('.btn-remove');
        
        this.setLoading(removeButton, true);
        
        try {
            const formData = new FormData();
            formData.append('_method', 'DELETE');
            
            const response = await fetch(`/cart/remove/${cartId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showAlert('success', data.message);
                
                // Update cart count in navbar
                if (window.updateCartCount) {
                    window.updateCartCount();
                }
                
                // Remove row from table
                const row = form.closest('tr');
                if (row) {
                    row.style.opacity = '0';
                    setTimeout(() => {
                        row.remove();
                        // Check if cart is empty
                        if (document.querySelectorAll('tbody tr').length === 0) {
                            location.reload();
                        }
                    }, 300);
                } else {
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            } else {
                this.showAlert('danger', data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('danger', 'Terjadi kesalahan saat menghapus item');
        } finally {
            this.setLoading(removeButton, false);
        }
    }

    updateSubtotal(cartId, subtotal) {
        const subtotalElement = document.querySelector(`.subtotal-${cartId}`);
        if (subtotalElement) {
            subtotalElement.textContent = this.formatRupiah(subtotal);
            
            // Add highlight effect
            subtotalElement.style.backgroundColor = '#f0f0f0';
            setTimeout(() => {
                subtotalElement.style.backgroundColor = '';
            }, 500);
        }
    }

    formatRupiah(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }

    showAlert(type, message) {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.alert');
        existingAlerts.forEach(alert => alert.remove());
        
        // Create new alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        
        // Insert alert
        const container = document.querySelector('.cart-container');
        const header = document.querySelector('.cart-header');
        container.insertBefore(alertDiv, header.nextSibling);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            alertDiv.style.opacity = '0';
            setTimeout(() => alertDiv.remove(), 300);
        }, 3000);
    }

    setLoading(button, isLoading) {
        if (!button) return;
        
        if (isLoading) {
            button.classList.add('btn-loading');
            button.disabled = true;
            button.originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
        } else {
            button.classList.remove('btn-loading');
            button.disabled = false;
            if (button.originalText) {
                button.innerHTML = button.originalText;
            }
        }
    }
}

// Initialize cart when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new CartManager();
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CartManager;
}