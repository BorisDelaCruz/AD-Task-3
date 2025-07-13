// Check if user is logged in before adding event listeners
if (document.getElementById('logoutForm')) {
    // Enhanced logout form submission with frontend-backend connection
    document.getElementById('logoutForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        
        // Confirmation dialog
        if (!confirm('Are you sure you want to logout?')) {
            return;
        }
        
        const form = this;
        const formData = new FormData(form);
        const logoutBtn = document.getElementById('logoutBtn');
        const btnText = logoutBtn.querySelector('.btn-text');
        const loadingText = document.getElementById('loadingText');
        const messageContainer = document.getElementById('message-container');
        
        // Clear previous messages
        messageContainer.innerHTML = '';
        
        // Show loading state
        btnText.style.display = 'none';
        loadingText.style.display = 'inline';
        logoutBtn.disabled = true;
        
        // BACKEND CONNECTION: Send POST request to handler
        fetch('/handlers/auth.handler.php', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest' // Indicate AJAX request
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Success: Show success message and redirect
                messageContainer.innerHTML = `
                    <div class="success">
                        ${data.message}
                    </div>
                `;
                
                // Redirect after short delay
                setTimeout(() => {
                    window.location.href = data.redirect || '/pages/login/';
                }, 1000);
                
            } else {
                // Error: Show error message
                messageContainer.innerHTML = `
                    <div class="error">
                        ${data.message}
                    </div>
                `;
                
                // Reset form state
                btnText.style.display = 'inline';
                loadingText.style.display = 'none';
                logoutBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Logout error:', error);
            messageContainer.innerHTML = `
                <div class="error">
                    An unexpected error occurred. Please try again.
                </div>
            `;
            
            // Reset form state
            btnText.style.display = 'inline';
            loadingText.style.display = 'none';
            logoutBtn.disabled = false;
        });
    });
}

// Auto-logout warning for long sessions
// This will be populated by PHP when rendering the page
function initSessionWarning(sessionDuration) {
    if (sessionDuration > 3300) { // 55 minutes
        alert('Your session will expire soon. Please save any work before logging out.');
    }
}