// Function to auto-fill login credentials
function fillLogin(username, password) {
    document.getElementById('username').value = username;
    document.getElementById('password').value = password;
}

// Enhanced form submission with frontend-backend connection
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent default form submission
    
    const form = this;
    const formData = new FormData(form);
    const loginBtn = document.getElementById('loginBtn');
    const btnText = loginBtn.querySelector('.btn-text');
    const loadingText = document.getElementById('loadingText');
    const messageContainer = document.getElementById('message-container');
    
    // Clear previous messages
    messageContainer.innerHTML = '';
    
    // Show loading state
    btnText.style.display = 'none';
    loadingText.style.display = 'inline';
    loginBtn.disabled = true;
    
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
                window.location.href = data.redirect || '/pages/dashboard/';
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
            loginBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Login error:', error);
        messageContainer.innerHTML = `
            <div class="error">
                An unexpected error occurred. Please try again.
            </div>
        `;
        
        // Reset form state
        btnText.style.display = 'inline';
        loadingText.style.display = 'none';
        loginBtn.disabled = false;
    });
});

// Form validation on input
const usernameInput = document.getElementById('username');
const passwordInput = document.getElementById('password');

function validateForm() {
    const username = usernameInput.value.trim();
    const password = passwordInput.value;
    const loginBtn = document.getElementById('loginBtn');
    
    if (username && password) {
        loginBtn.style.opacity = '1';
        loginBtn.style.cursor = 'pointer';
    } else {
        loginBtn.style.opacity = '0.7';
        loginBtn.style.cursor = 'not-allowed';
    }
}

usernameInput.addEventListener('input', validateForm);
passwordInput.addEventListener('input', validateForm);

// Initial validation
validateForm();