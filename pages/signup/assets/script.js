// Enhanced form validation and submission
document.getElementById('signupForm').addEventListener('submit', function(e) {
    const form = this;
    const signupBtn = document.getElementById('signupBtn');
    const btnText = signupBtn.querySelector('.btn-text');
    const loadingText = document.getElementById('loadingText');
    const messageContainer = document.getElementById('message-container');
    
    // Clear previous messages
    messageContainer.innerHTML = '';
    
    // Get form data for validation
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const firstName = document.getElementById('first_name').value.trim();
    const lastName = document.getElementById('last_name').value.trim();
    
    // Frontend validation
    if (!username || !password || !firstName || !lastName) {
        e.preventDefault();
        messageContainer.innerHTML = `
            <div class="error">
                Please fill in all required fields (marked with *).
            </div>
        `;
        return;
    }
    
    if (password.length < 6) {
        e.preventDefault();
        messageContainer.innerHTML = `
            <div class="error">
                Password must be at least 6 characters long.
            </div>
        `;
        return;
    }
    
    if (username.length < 3) {
        e.preventDefault();
        messageContainer.innerHTML = `
            <div class="error">
                Username must be at least 3 characters long.
            </div>
        `;
        return;
    }
    
    // Show loading state
    btnText.style.display = 'none';
    loadingText.style.display = 'inline';
    signupBtn.disabled = true;
});

// Real-time validation feedback
function validateField(field, minLength = 1) {
    const value = field.value.trim();
    const isValid = value.length >= minLength;
    
    field.style.borderColor = isValid ? '#28a745' : '#dc3545';
    field.style.boxShadow = isValid ? '0 0 0 0.2rem rgba(40, 167, 69, 0.25)' : '0 0 0 0.2rem rgba(220, 53, 69, 0.25)';
    
    return isValid;
}

// Add real-time validation to required fields
document.getElementById('username').addEventListener('blur', function() {
    validateField(this, 3);
});

document.getElementById('password').addEventListener('blur', function() {
    validateField(this, 6);
});

document.getElementById('first_name').addEventListener('blur', function() {
    validateField(this, 1);
});

document.getElementById('last_name').addEventListener('blur', function() {
    validateField(this, 1);
});

// Form validation on input
function validateForm() {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const firstName = document.getElementById('first_name').value.trim();
    const lastName = document.getElementById('last_name').value.trim();
    const signupBtn = document.getElementById('signupBtn');
    
    const isValid = username.length >= 3 && 
                  password.length >= 6 && 
                  firstName.length >= 1 && 
                  lastName.length >= 1;
    
    if (isValid) {
        signupBtn.style.opacity = '1';
        signupBtn.style.cursor = 'pointer';
    } else {
        signupBtn.style.opacity = '0.7';
        signupBtn.style.cursor = 'not-allowed';
    }
}

// Add input listeners for real-time validation
['username', 'password', 'first_name', 'last_name'].forEach(id => {
    document.getElementById(id).addEventListener('input', validateForm);
});

// Initial validation
validateForm();