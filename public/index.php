<!DOCTYPE html>
<html>
<head>
    <title>NeuroHelp | Login or Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
            width: 360px;
        }
        h2 {
            text-align: center;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="date"],
        textarea,
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        .toggle {
            text-align: center;
            margin-top: 1rem;
        }
        .toggle a {
            text-decoration: none;
            color: #007bff;
        }
        .consent {
            margin: 10px 0;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 id="form-title">Login</h2>

    <!-- Login Form -->
    <form id="form" action="../auth/login.php" method="POST">
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />
        <input type="submit" value="Login" />
    </form>

    <p><a href="../auth/forgot_password.php">Forgot Password?</a></p>

    <!-- Register Form (Hidden by Default) -->
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

        <input type="submit" value="Register" />
    </form>

    <!-- Toggle Link -->
    <div class="toggle">
        <span id="toggle-text">Don't have an account?</span>
        <a href="#" onclick="toggleForm()">Register</a>
    </div>
</div>

<!-- JavaScript to Toggle Forms -->
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
