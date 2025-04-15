document.getElementById('loginform').addEventListener('submit', async (e) => {
    e.preventDefault();

    const messageDiv = document.getElementById('message');
    messageDiv.innerText = '';

    const data = {
        email: document.getElementById('email').value,
        password: document.getElementById('password').value
    };

    const conn = new XMLHttpRequest();
    conn.open('POST', 'http://127.0.0.1:8000/api/auth/login', true);
    conn.setRequestHeader('Content-Type', 'application/json');
    conn.setRequestHeader('Accept', 'application/json');

    conn.onreadystatechange = function () {
        if (conn.readyState === 4) {
            try {
                const response = JSON.parse(conn.responseText);

                if (conn.status === 201) {
                    localStorage.setItem('token', response.token);
                    messageDiv.classList.remove('text-red-500');
                    messageDiv.classList.add('text-green-600');
                    messageDiv.innerText = response.message;

                    setTimeout(() => {
                        if (response.user.is_admin) {
                            window.location.href = '/dashboard';
                        } else {
                            window.location.href = '/films';
                        }
                    }, 1000);
                } else {
                    messageDiv.classList.remove('text-green-600');
                    messageDiv.classList.add('text-red-500');
                    messageDiv.innerText = response.error || "Invalid email or password";
                }

            } catch (err) {
                messageDiv.classList.add('text-red-500');
                messageDiv.innerText = 'Something went wrong. Please try again.';
                console.error('Error parsing JSON', err);
            }
        }
    };

    conn.onerror = function () {
        messageDiv.classList.add('text-red-500');
        messageDiv.innerText = 'Network error. Please check your connection.';
    };

    conn.send(JSON.stringify(data));
});
