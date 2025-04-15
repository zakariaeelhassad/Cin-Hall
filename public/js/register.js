
const API_BASE_URL = 'http://127.0.0.1:8000/api';

window.addEventListener('load', () => {
    console.log("JS loaded! ✅");

    const form = document.getElementById('signupForm');
    const errorMessage = document.getElementById('errorMessage');

    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        console.log("Form submitted ✅");

        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm-password').value;
        const role = document.getElementById('role').value;

        if (password !== confirmPassword) {
            errorMessage.textContent = 'Passwords do not match';
            return;
        }

        if (!role) {
            errorMessage.textContent = 'Please select an account type';
            return;
        }

        try {
            const response = await fetch(`${API_BASE_URL}/auth/register`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ name, email, password, role })
            });

            const result = await response.json();
            console.log("API Response:", result);

            if (result.status === 'success') {
                localStorage.setItem('token', result.token);
                alert('Signup Successful!');
                window.location.href = 'films';
            } else {
                if (result.errors) {
                    const messages = Object.values(result.errors).flat();
                    errorMessage.textContent = messages.join(', ');
                } else {
                    errorMessage.textContent = result.message || 'Signup failed.';
                }
            }
        } catch (err) {
            console.error("Signup error:", err);
            errorMessage.textContent = "An unexpected error occurred.";
        }
    });
    return false;
});
