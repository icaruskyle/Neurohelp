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
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Profile | NeuroHelp</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #c0f0e8, #e2d1f9);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .form-container {
      background-color: #fff;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 450px;
    }

    .form-container h2 {
      margin-bottom: 20px;
      color: #4b2aad;
      text-align: center;
    }

    label {
      font-weight: bold;
      font-size: 14px;
      display: block;
      margin-top: 10px;
      color: #444;
    }

    input[type="text"],
    input[type="email"],
    input[type="date"] {
      width: 100%;
      padding: 12px;
      margin-top: 6px;
      margin-bottom: 14px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 14px;
    }

    input[type="submit"],
    button {
      width: 100%;
      padding: 12px;
      background-color: #7a42f4;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      margin-top: 10px;
      transition: background-color 0.3s ease;
      font-size: 14px;
    }

    input[type="submit"]:hover,
    button:hover {
      background-color: #5d2cd3;
    }

    .back-btn {
      background-color: #999;
    }

    .back-btn:hover {
      background-color: #666;
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

      <a href="dashboard1.php" style="text-decoration: none;">
        <button type="button" class="back-btn">⬅ Back to Dashboard</button>
      </a>
    </form>
  </div>
</body>
</html>
