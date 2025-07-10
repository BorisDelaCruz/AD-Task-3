/**
 * Main JavaScript for AD-Task-3
 */

// Global configuration
const AppConfig = {
    baseUrl: '',
    ajaxTimeout: 30000,
    animationDuration: 300
};

// Utility functions
const Utils = {
    /**
     * Show loading state on an element
     */
    showLoading: function(element, text = 'Loading...') {
        const $element = $(element);
        $element.addClass('loading');
        $element.prop('disabled', true);
        
        if ($element.is('button')) {
            $element.data('original-text', $element.html());
            $element.html(`<i class="fas fa-spinner fa-spin"></i> ${text}`);
        }
    },
    
    /**
     * Hide loading state from an element
     */
    hideLoading: function(element) {
        const $element = $(element);
        $element.removeClass('loading');
        $element.prop('disabled', false);
        
        if ($element.is('button') && $element.data('original-text')) {
            $element.html($element.data('original-text'));
        }
    },
    
    /**
     * Show a toast notification
     */
    showToast: function(message, type = 'info', duration = 5000) {
        const toastHtml = `
            <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-${this.getToastIcon(type)}"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        // Create toast container if it doesn't exist
        if (!$('#toast-container').length) {
            $('body').append('<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>');
        }
        
        const $toast = $(toastHtml);
        $('#toast-container').append($toast);
        
        // Initialize and show toast
        const toast = new bootstrap.Toast($toast[0], {
            autohide: true,
            delay: duration
        });
        toast.show();
        
        // Remove toast element after it's hidden
        $toast.on('hidden.bs.toast', function() {
            $(this).remove();
        });
    },
    
    /**
     * Get appropriate icon for toast type
     */
    getToastIcon: function(type) {
        const icons = {
            'success': 'check-circle',
            'danger': 'exclamation-triangle',
            'warning': 'exclamation-circle',
            'info': 'info-circle',
            'primary': 'bell',
            'secondary': 'bell'
        };
        return icons[type] || 'bell';
    },
    
    /**
     * Format date for display
     */
    formatDate: function(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    },
    
    /**
     * Confirm action with user
     */
    confirmAction: function(message, callback) {
        if (confirm(message)) {
            callback();
        }
    },
    
    /**
     * Handle AJAX errors
     */
    handleAjaxError: function(xhr, status, error) {
        console.error('AJAX Error:', error);
        
        let message = 'An error occurred. Please try again.';
        
        if (xhr.status === 401) {
            message = 'Session expired. Please login again.';
            setTimeout(() => {
                window.location.href = '/pages/login/';
            }, 2000);
        } else if (xhr.status === 403) {
            message = 'Access denied. You do not have permission to perform this action.';
        } else if (xhr.status === 404) {
            message = 'Resource not found.';
        } else if (xhr.status >= 500) {
            message = 'Server error. Please try again later.';
        }
        
        this.showToast(message, 'danger');
    }
};

// Form validation helper
const FormValidator = {
    /**
     * Validate required fields
     */
    validateRequired: function(form) {
        let isValid = true;
        const $form = $(form);
        
        $form.find('[required]').each(function() {
            const $field = $(this);
            if (!$field.val().trim()) {
                $field.addClass('is-invalid');
                isValid = false;
            } else {
                $field.removeClass('is-invalid');
            }
        });
        
        return isValid;
    },
    
    /**
     * Validate email format
     */
    validateEmail: function(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    },
    
    /**
     * Clear form validation
     */
    clearValidation: function(form) {
        $(form).find('.is-invalid').removeClass('is-invalid');
        $(form).find('.is-valid').removeClass('is-valid');
    }
};

// Global event handlers
$(document).ready(function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Auto-hide alerts after 5 seconds
    $('.alert-dismissible').each(function() {
        const $alert = $(this);
        setTimeout(() => {
            $alert.fadeOut();
        }, 5000);
    });
    
    // Form validation on submit
    $('form[data-validate="true"]').on('submit', function(e) {
        if (!FormValidator.validateRequired(this)) {
            e.preventDefault();
            Utils.showToast('Please fill in all required fields.', 'warning');
        }
    });
    
    // Clear validation on input
    $('input, textarea, select').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
    
    // Confirm delete actions
    $('[data-confirm]').on('click', function(e) {
        const message = $(this).data('confirm') || 'Are you sure?';
        if (!confirm(message)) {
            e.preventDefault();
        }
    });
    
    // Auto-save form data to localStorage
    $('form[data-auto-save="true"]').each(function() {
        const form = this;
        const formId = $(form).attr('id') || 'default-form';
        
        // Load saved data
        const savedData = localStorage.getItem(`form-${formId}`);
        if (savedData) {
            try {
                const data = JSON.parse(savedData);
                Object.keys(data).forEach(key => {
                    const field = form.querySelector(`[name="${key}"]`);
                    if (field) {
                        field.value = data[key];
                    }
                });
            } catch (e) {
                console.error('Error loading saved form data:', e);
            }
        }
        
        // Save data on input
        $(form).on('input change', function() {
            const formData = {};
            $(form).serializeArray().forEach(item => {
                formData[item.name] = item.value;
            });
            localStorage.setItem(`form-${formId}`, JSON.stringify(formData));
        });
        
        // Clear saved data on successful submit
        $(form).on('submit', function() {
            localStorage.removeItem(`form-${formId}`);
        });
    });
    
    // Smooth scrolling for anchor links
    $('a[href^="#"]').on('click', function(e) {
        const target = $(this.getAttribute('href'));
        if (target.length) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top - 70
            }, 500);
        }
    });
    
    // Back to top button
    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $('#back-to-top').fadeIn();
        } else {
            $('#back-to-top').fadeOut();
        }
    });
    
    // Add back to top button if it doesn't exist
    if (!$('#back-to-top').length) {
        $('body').append(`
            <button id="back-to-top" class="btn btn-primary position-fixed d-none" style="bottom: 20px; right: 20px; z-index: 1000;">
                <i class="fas fa-arrow-up"></i>
            </button>
        `);
    }
    
    $('#back-to-top').on('click', function() {
        $('html, body').animate({scrollTop: 0}, 500);
    });
});

// Global AJAX setup
$.ajaxSetup({
    timeout: AppConfig.ajaxTimeout,
    beforeSend: function(xhr, settings) {
        // Add CSRF token if available
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (csrfToken) {
            xhr.setRequestHeader('X-CSRF-Token', csrfToken);
        }
    },
    error: function(xhr, status, error) {
        Utils.handleAjaxError(xhr, status, error);
    }
});

// Export utilities to global scope
window.Utils = Utils;
window.FormValidator = FormValidator;
window.AppConfig = AppConfig;
