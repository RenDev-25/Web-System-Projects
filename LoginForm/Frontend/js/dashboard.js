// frontend/js/dashboard.js

document.addEventListener('DOMContentLoaded', async () => {
    
    // We want to check auth status immediately
    await verifyAuthentication();

    // Attach event listener to the logout button
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', handleLogout);
    }

    // -----------------------------------------
    // Functions
    // -----------------------------------------

    async function verifyAuthentication() {
        try {
            // Send a GET request to check the session
            const response = await fetch(`${API_BASE_URL}/check_auth.php`);
            const data = await response.json();

            if (response.ok && data.success) {
                // User is authenticated: Update the UI with their name
                document.getElementById('userGreeting').textContent = `Hello, ${data.data.fullname}`;
                
                // Show a welcome message in the dashboard alert area
                showAlert('dashboardAlert', 'Session active. Welcome to your secure dashboard.', 'success');
            } else {
                // User is not authenticated: Redirect to login
                window.location.href = 'index.html';
            }
        } catch (error) {
            console.error("Authentication check failed:", error);
            window.location.href = 'index.html';
        }
    }

    async function handleLogout() {
        // Disable the button to prevent multiple clicks
        const logoutBtn = document.getElementById('logoutBtn');
        logoutBtn.disabled = true;
        logoutBtn.textContent = 'Logging out...';

        try {
            // Send a POST request to the logout endpoint
            const response = await fetch(`${API_BASE_URL}/logout.php`, {
                method: 'POST'
            });

            const data = await response.json();

            if (response.ok) {
                // Successfully logged out, clear the screen and redirect
                window.location.href = 'index.html';
            } else {
                showAlert('dashboardAlert', 'Failed to log out. Please try again.', 'danger');
                logoutBtn.disabled = false;
                logoutBtn.textContent = 'Logout';
            }
        } catch (error) {
            console.error("Logout failed:", error);
            showAlert('dashboardAlert', 'A network error occurred. Please try again.', 'danger');
            logoutBtn.disabled = false;
            logoutBtn.textContent = 'Logout';
        }
    }
});