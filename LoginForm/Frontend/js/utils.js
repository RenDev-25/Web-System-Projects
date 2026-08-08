
const API_BASE_URL = '../backend/api';

/**
 * 
 * 
 * @param {string} elementId - The ID of the alert container in the HTML
 * @param {string} message - The message to display
 * @param {string} type - Bootstrap context color (e.g., 'danger', 'success')
 */
function showAlert(elementId, message, type = 'danger') {
    const alertBox = document.getElementById(elementId);
    if (!alertBox) return;
    
    
    alertBox.className = `alert alert-${type}`;
    alertBox.textContent = message;
    
    
    alertBox.classList.remove('d-none');
}

/**
 * 
 * 
 * @param {string} elementId 
 */
function hideAlert(elementId) {
    const alertBox = document.getElementById(elementId);
    if (!alertBox) return;
    
    alertBox.classList.add('d-none');
    alertBox.textContent = '';
}