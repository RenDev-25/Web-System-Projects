// frontend/js/login.js

document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        hideAlert('loginAlert');

        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;

      
        if (!email || !password) {
            showAlert('loginAlert', 'Please fill in all fields.', 'danger');
            return;
        }

     
        loginBtn.disabled = true;
        loginBtn.textContent = 'Logging in...';

        try {
            const payload = {
                email: email,
                password: password
            };

            const response = await fetch(`${API_BASE_URL}/login.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Login successful, redirect immediately to the protected dashboard
                window.location.href = 'dashboard.html';
            } else {
                // Show error message (e.g., "Invalid email or password")
                showAlert('loginAlert', data.message, 'danger');
                loginBtn.disabled = false;
                loginBtn.textContent = 'Login';
            }
            
        } catch (error) {
            showAlert('loginAlert', 'A network error occurred. Please try again.', 'danger');
            loginBtn.disabled = false;
            loginBtn.textContent = 'Login';
        }
    });
});