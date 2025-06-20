<?php
require_once __DIR__ . '/../includes/db.php';
session_start();

// Check if user is logged in
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
  header("Location: ../auth/login.php");
  exit;
}

// Fetch user info
$stmt = $conn->prepare("SELECT full_name, birthday, mobile, address, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>NeuroHelp Dashboard</title>
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>
  <div class="sidebar">
    <img src="logon.png" alt="NeuroHelp Logo" class="logo" />
    <h1>NeuroHelp</h1>
    <p>Your AI-Driven Mental Health Care Companion</p>
    <ul class="nav-buttons">
      <li><a href="dashboard1.php">Home</a></li>
      <li><a href="update.html">Explore</a></li>
      <li><a href="update.html">Daily</a></li>
      <li><a href="journal.html">Heneuro</a></li>
      <li><a href="chat.html">SNS</a></li>
      <li><a href="edit_profile.php">Profile</a></li>
      <li><a href="../auth/logout.php">Logout</a></li>
    </ul>
  </div>

  <main class="main-content">
    <div class="dashboard">
      <!-- Regular Tiles -->

          <!-- User Info -->
      <!-- User Info -->
<div class="profile-card">
  <h3> Welcome, <?= htmlspecialchars($user['full_name']) ?>!</h3>
  <h4><strong>👤Your Profile</strong></h4>
  <ul>
    <li><strong>Birthday:</strong> <?= htmlspecialchars($user['birthday']) ?></li>
    <li><strong>Mobile:</strong> <?= htmlspecialchars($user['mobile']) ?></li>
    <li><strong>Address:</strong> <?= htmlspecialchars($user['address']) ?></li>
    <li><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></li>
  </ul>
</div>

      <br>
      <br>
      <br>

      <div class="tile" onclick="location.href='journal.html'">
        <img src="daily.png" alt="Daily Journal">
        <p>DAILY JOURNAL</p>
      </div>
      <div class="tile" onclick="location.href='chat.html'">
        <img src="chat.png" alt="Chatbot" />
        <p>CHAT WITH NEURO</p>
      </div>
      <div class="tile" onclick="location.href='update.html'">
        <img src="bell.png" alt="Updates" />
        <p>UPDATES</p>
      </div>
      <div class="tile" onclick="location.href='podcast.html'">
        <img src="music.png" alt="Podcast" />
        <p>PODCAST</p>
      </div>


      <!-- View Logs -->
      <div class="tile" onclick="location.href='mental_logs.php'">
        <img src="logon.png" alt="Logs" />
        <p>🧠 View Mental Health Logs</p>
      </div>

      <!-- RSA Test -->
      <div class="tile" onclick="location.href='../test_rsa_keys.php'">
        <img src="locks.png" alt="RSA" />
        <p>🔐 Test RSA Keys</p>
      </div>
    </div>
  </main>

  <script>
    const params = new URLSearchParams(window.location.search);
    if (params.get('updated') === '1') {
        alert("✅ Profile updated successfully!");
        window.history.replaceState({}, document.title, window.location.pathname);
    }
  </script>
</body>
</html>
