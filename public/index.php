<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeuroHelp | Login or Register</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #eef2f5;
        padding: 1rem;
    }

    .container {
        display: flex;
        flex-direction: row;
        max-width: 1000px;
        width: 100%;
        height: auto;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        border-radius: 20px;
        overflow: hidden;
        background: #fff;
        flex-wrap: wrap;
    }

    .left-panel,
    .right-panel {
        flex: 1;
        min-width: 300px;
        padding: 2rem;
        box-sizing: border-box;
    }

    .left-panel {
        background-color: #ffffff;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .left-panel img {
        width: 80%;
        max-width: 250px;
        height: auto;
        margin-bottom: 20px;
    }

    .left-panel h1 {
        font-size: 2em;
        color: #111;
        margin-bottom: 10px;
    }

    .left-panel p {
        font-size: 1.1em;
        color: #333;
    }

    .right-panel {
        background: linear-gradient(to right, #a2c0f9, #bda8f9);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .login-form {
        background-color: rgba(255, 255, 255, 0.95);
        padding: 30px;
        border-radius: 15px;
        width: 100%;
        max-width: 350px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .login-form h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    .login-form input[type="text"],
    .login-form input[type="email"],
    .login-form input[type="password"],
    .login-form input[type="date"],
    .login-form textarea,
    .login-form input[type="submit"] {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        margin-bottom: 10px;
        border-radius: 8px;
        border: 1px solid #ccc;
    }

    .login-form label {
        display: block;
        margin-top: 10px;
    }

    .button {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: none;
        border-radius: 30px;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .login-btn {
        background-color: #bb86fc;
        color: white;
        border: 2px solid #bb86fc;
    }

    .login-btn:hover {
        background-color: #a46ae0;
    }

    .toggle {
        text-align: center;
        margin-top: 1rem;
    }

    .toggle a {
        text-decoration: none;
        color: #007bff;
    }

    a.forgot {
        font-size: 13px;
        color: #111;
        text-decoration: none;
        text-align: center;
        display: block;
        margin-top: 10px;
    }

    a.forgot:hover {
        text-decoration: underline;
    }

    .consent {
        margin: 10px 0;
        font-size: 0.9em;
    }

    .right-panel .back-button {
        position: absolute;
        top: 20px;
        right: 30px;
        font-size: 18px;
        background-color: #fff;
        border: none;
        padding: 8px 12px;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
    }

    .right-panel .back-button:hover {
        background-color: #f1f1f1;
    }

    /* ✅ Responsive Styles for Mobile */
    @media (max-width: 768px) {
        .container {
            flex-direction: column;
            height: auto;
        }

        .left-panel,
        .right-panel {
            width: 100%;
            padding: 1.5rem;
        }

        .left-panel img {
            max-width: 200px;
        }

        .right-panel {
            min-height: auto;
        }
    }
</style>

</head>
<body>
<div class="container">

    <!-- Left Panel -->
    <div class="left-panel">
        <img src="logon.jpg" alt="NeuroHelp Logo">
        <h1>Welcome to NeuroHelp</h1>
        <p>Your companion in mental health support and guidance.</p>
    </div>

    <!-- Right Panel -->
    <div class="right-panel">
        <div class="login-form">
            <h2 id="form-title">Login</h2>

            <!-- Login Form -->
            <form id="form" action="../auth/login.php" method="POST">
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <input type="submit" class="login-btn" value="Login" />
                <a class="forgot" href="../auth/forgot_password.php">Forgot Password?</a>
            </form>

            <!-- Register Form -->
            <form id="register-form" action="../auth/register.php" method="POST" style="display: none;">
                <input type="text" name="full_name" placeholder="Full Name" required />
                <input type="date" name="birthday" required />
                <input type="text" name="mobile" placeholder="Mobile Number" required />
                <input type="text" name="address" placeholder="Address" required />
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                
                <div class="consent">
                    <input type="checkbox" name="consent" value="1" required />
                    <label for="consent">I consent to data collection and usage.</label>
                </div>

                <input type="submit" class="login-btn" value="Register" />
            </form>

            <!-- Toggle Form -->
            <div class="toggle">
                <span id="toggle-text">Don't have an account?</span>
                <a href="#" onclick="toggleForm()">Register</a>
            </div>
        </div>
    </div>
</div>

<script>
function toggleForm() {
    const loginForm = document.getElementById('form');
    const registerForm = document.getElementById('register-form');
    const title = document.getElementById('form-title');
    const toggleText = document.getElementById('toggle-text');
    const toggleLink = document.querySelector('.toggle a');

    if (loginForm.style.display === 'none') {
        loginForm.style.display = 'block';
        registerForm.style.display = 'none';
        title.innerText = 'Login';
        toggleText.innerText = "Don't have an account?";
        toggleLink.innerText = 'Register';
    } else {
        loginForm.style.display = 'none';
        registerForm.style.display = 'block';
        title.innerText = 'Register';
        toggleText.innerText = 'Already have an account?';
        toggleLink.innerText = 'Login';
    }
}

const params = new URLSearchParams(window.location.search);
if (params.get('registered') === '1') {
    alert("✅ Registration successful! Please log in.");
    window.history.replaceState({}, document.title, window.location.pathname);
}
if (params.get('logout') === '1') {
    alert("👋 You have been logged out successfully.");
    window.history.replaceState({}, document.title, window.location.pathname);
}
</script>
</body>
</html>
