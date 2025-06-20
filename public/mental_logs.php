<?php
session_start();
require_once __DIR__ . '/../middlewares/require_2fa.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/secure.php';

$config = include __DIR__ . '/../config/config.php';
$secure = include __DIR__ . '/../config/secure.php';
$key = $secure['encryption_key'];
$cipher = $secure['cipher'];

$conn = new mysqli(
    $config['db']['host'],
    $config['db']['username'],
    $config['db']['password'],
    $config['db']['database']
);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}
$user_id = $_SESSION['user_id'];

// Handle new journal entry
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mood = $_POST['mood'];
    $journal = $_POST['journal'];

    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
    $encrypted_journal = openssl_encrypt($journal, $cipher, $key, 0, $iv);
    $iv_encoded = base64_encode($iv);

    $stmt = $conn->prepare("INSERT INTO mental_health_logs (user_id, mood, journal, iv) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $mood, $encrypted_journal, $iv_encoded);

    if ($stmt->execute()) {
        header("Location: mental_logs.php?saved=1");
        exit;
    } else {
        $error = "Failed to save journal entry.";
    }
}

// Fetch logs
$stmt = $conn->prepare("SELECT created_at, mood, journal, iv FROM mental_health_logs WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mental Health Logs</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f8ff;
            margin: 0;
            padding: 40px;
        }

        h2, h3 {
            color: #4b2aad;
        }

        form {
            background: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
        }

        select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        button {
            padding: 10px 20px;
            background-color: #7a42f4;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background-color: #5d2cd3;
        }

        .back-btn {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
        }

        .back-btn button {
            background-color: #ccc;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #e2d1f9;
            color: #333;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            margin-bottom: 10px;
        }

        ul li a {
            text-decoration: none;
            color: #4b2aad;
        }

        ul li a:hover {
            text-decoration: underline;
        }

        .success-msg {
            color: green;
        }

        .error-msg {
            color: red;
        }
    </style>
</head>
<body>
    <h2>Mental Health Journal & Mood Tracker</h2>

    <?php if (isset($_GET['saved'])): ?>
        <p class="success-msg">✅ Your journal entry was saved.</p>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <p class="error-msg"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="mood">Mood:</label>
        <select name="mood" required>
            <option value="">--Select--</option>
            <option value="Happy">😊 Happy</option>
            <option value="Sad">😢 Sad</option>
            <option value="Anxious">😟 Anxious</option>
            <option value="Angry">😠 Angry</option>
            <option value="Excited">😃 Excited</option>
            <option value="Tired">😴 Tired</option>
        </select>

        <label for="journal">Your thoughts:</label>
        <textarea name="journal" rows="5" required></textarea>

        <button type="submit">Save Entry</button>
    </form>

    <!-- Back Button -->
    <a href="dashboard1.php" class="back-btn">
        <button>⬅ Back to Dashboard</button>
    </a>

    <hr>

    <h3>Self-Help Tools 🧘</h3>
    <ul>
        <li><a href="https://www.youtube.com/watch?v=inpok4MKVLM" target="_blank">10-Minute Guided Meditation</a></li>
        <li><a href="https://www.verywellmind.com/how-to-practice-deep-breathing-5207086" target="_blank">Breathing Exercise</a></li>
        <li><a href="https://www.healthline.com/health/mind-body/motivational-quotes" target="_blank">Daily Motivational Quotes</a></li>
    </ul>

    <hr>

    <h2>Your Journal History</h2>
    <table>
        <tr>
            <th>Date</th>
            <th>Mood</th>
            <th>Journal</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php
                $iv = base64_decode($row['iv']);
                $decrypted_journal = openssl_decrypt($row['journal'], $cipher, $key, 0, $iv);
            ?>
            <tr>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td><?= htmlspecialchars($row['mood']) ?></td>
                <td><?= nl2br(htmlspecialchars($decrypted_journal)) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
