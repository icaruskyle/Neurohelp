<?php
require_once __DIR__ . '/../middlewares/require_2fa.php';
require_once __DIR__ . '/../includes/db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: index.php");
    exit;
}

// If form submitted, process the update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $birthday = $_POST['birthday'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("UPDATE users SET full_name = ?, birthday = ?, mobile = ?, address = ?, email = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $full_name, $birthday, $mobile, $address, $email, $user_id);
    $stmt->execute();

    // Redirect back to dashboard with success message
    header("Location: dashboard.php?updated=1");
    exit;
}

// Fetch current user info
$stmt = $conn->prepare("SELECT full_name, birthday, mobile, address, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
<style>
    body {
        font-family: Arial;
        background: #f4f7fa;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 1rem;
    }

    .form-container {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 0 12px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 400px;
    }

    input[type="text"],
    input[type="email"],
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
        font-weight: bold;
        display: block;
        margin-top: 10px;
    }

    .back-button {
        width: 100%;
        margin-top: 15px;
        padding: 10px;
        border-radius: 8px;
        background-color: #e0e0e0;
        border: none;
        cursor: pointer;
    }

    @media (max-width: 500px) {
        .form-container {
            padding: 1rem;
            border-radius: 8px;
        }

        input, .back-button {
            font-size: 16px;
        }
    }
</style>
</head>
<body>
<div class="form-container">
    <h2>Edit Profile</h2>
    <form method="POST">
        <label>Full Name</label>
        <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>

        <label>Birthday</label>
        <input type="date" name="birthday" value="<?= htmlspecialchars($user['birthday']) ?>" required>

        <label>Mobile</label>
        <input type="text" name="mobile" value="<?= htmlspecialchars($user['mobile']) ?>" required>

        <label>Address</label>
        <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <input type="submit" value="Update Profile">

        <a href="dashboard.php" style="text-decoration:none;">
    <button type="button">⬅ Go Back to Dashboard</button>
</a>
    </form>
</div>
</body>
</html>
