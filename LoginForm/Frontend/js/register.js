// frontend/js/register.js

document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.getElementById('registerForm');
    const registerBtn = document.getElementById('registerBtn');

    registerForm.addEventListener('submit', async (e) => {
      
        e.preventDefault();

        
        hideAlert('registerAlert');

        
        const fullname = document.getElementById('fullname').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const confirm_password = document.getElementById('confirm_password').value;

    
        if (password !== confirm_password) {
            showAlert('registerAlert', 'Passwords do not match.', 'danger');
            return;
        }

      
        if (password.length < 6) {
            showAlert('registerAlert', 'Password must be at least 6 characters long.', 'danger');
            return;
        }

      
        registerBtn.disabled = true;
        registerBtn.textContent = 'Registering...';

        try {
        
            const payload = {
                fullname: fullname,
                email: email,
                password: password,
                confirm_password: confirm_password
            };

            // Send POST request using Fetch API
            const response = await fetch(`${API_BASE_URL}/register.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });

           
            const data = await response.json();

            if (response.ok && data.success) {
                
                showAlert('registerAlert', data.message, 'success');
                registerForm.reset();
                
                setTimeout(() => {
                    window.location.href = 'index.html';
                }, 2000);
            } else {
          
                showAlert('registerAlert', data.message, 'danger');
                
              
                registerBtn.disabled = false;
                registerBtn.textContent = 'Register';
            }
            
        } catch (error) {
            
            showAlert('registerAlert', 'A network error occurred. Please try again.', 'danger');
            
            registerBtn.disabled = false;
            registerBtn.textContent = 'Register';
        }
    });
});