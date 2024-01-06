<div id='error_msg'>
<!--ここにエラーメッセージ-->
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginButton = document.getElementById('loginButton');
    const registerButton = document.getElementById('registerButton');

    const fetchToken = function(url, email, password) {
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email, password })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.token) {
                localStorage.setItem('userToken', data.token);
                document.getElementById('error_msg').textContent = "You Succeeded.";
            } else {
                console.error('Operation failed:', data.message);
                document.getElementById('error_msg').textContent = "You Failed.";
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('error_msg').textContent = "You Failed.";
        });
    };

    loginButton.addEventListener('click', function(event) {
        event.preventDefault();
        const url = 'http://techblog.shiroatohiro.com/api/login';
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        fetchToken(url, email, password);
    });

    registerButton.addEventListener('click', function(event) {
        event.preventDefault();
        const url = 'http://techblog.shiroatohiro.com/api/register';
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const password_confirmation = document.getElementById('password_confirmation').value;
        if(password === password_confirmation) {
            fetchToken(url, email, password);
        } else {
            document.getElementById('error_msg').textContent = "Password does not coincide.";
        }
    });
});
</script>
