<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_SESSION['2fa_verified'])) {
    header("Location: ../auth/send_otp.php?next=" . urlencode($_SERVER['PHP_SELF']));
    exit;
}

require_once __DIR__ . '/../includes/db.php';

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $conn->prepare("SELECT full_name, birthday, mobile, address, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch mental health logs
$stmt = $conn->prepare("SELECT created_at, log_entry FROM chat_logs WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$logs = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>NeuroHelp | Dashboard</title>
   <style>
    body {
        font-family: Arial;
        background-color: #f7f9fc;
        padding: 1rem;
    }

    .card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        max-width: 800px;
        margin: 0 auto 20px auto;
        width: 100%;
    }

    h2, h3 {
        margin-bottom: 10px;
    }

    .profile, .logs {
        margin-top: 20px;
    }

    .log-item {
        background: #eef2f5;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 10px;
    }

    .actions {
        margin-top: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .actions a {
        text-decoration: none;
        color: white;
        background: #007bff;
        padding: 10px 16px;
        border-radius: 8px;
        flex: 1;
        text-align: center;
    }

    .actions a.logout {
        background: #dc3545;
    }

    button {
        padding: 10px 16px;
        border-radius: 8px;
        background-color: #4CAF50;
        color: white;
        border: none;
        margin-top: 10px;
        width: 100%;
    }

    @media (max-width: 600px) {
        .card {
            padding: 15px;
        }

        .actions {
            flex-direction: column;
        }

        .actions a {
            width: 100%;
        }

        button {
            font-size: 16px;
        }
    }
</style>

</head>
<body>

<div class="card">
    <h2>Welcome, <?= htmlspecialchars($user['full_name']) ?></h2>

    <div class="profile">
        <h3>Your Profile</h3>
        <p><strong>Birthday:</strong> <?= $user['birthday'] ?></p>
        <p><strong>Mobile:</strong> <?= $user['mobile'] ?></p>
        <p><strong>Address:</strong> <?= $user['address'] ?></p>
        <p><strong>Email:</strong> <?= $user['email'] ?></p>
    </div>

    <div class="actions">
        <a href="edit_profile.php">✏️ Edit Profile</a>
        <a href="../auth/logout.php" class="logout">🚪 Logout</a>
    </div>
</div>

<a href="../test_rsa_keys.php" style="text-decoration: none;">
    <button style="padding: 10px 20px; border-radius: 6px; background-color: #4CAF50; color: white; border: none; margin-top: 10px;">
        🔐 Test RSA Keys
    </button>
</a>

<div class="card logs">
    <a href="mental_logs.php">
    <button style="padding: 10px 20px; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">
        🧠 View Mental Health Logs
    </button>
</a>
</div>
<script>
const params = new URLSearchParams(window.location.search);
if (params.get('updated') === '1') {
    alert("✅ Profile updated successfully!");
    window.history.replaceState({}, document.title, window.location.pathname);
}
</script>

</body>
</html>
