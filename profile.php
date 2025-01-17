<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    try {
        $stmt->execute([$hashed_password, $user['id']]);
        echo "Password updated successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
</head>
<body>
    <h2>Update Password</h2>
    <form method="POST">
        <label>New Password:</label>
        <input type="password" name="new_password" required><br>
        <button type="submit">Update Password</button>
    </form>
</body>
</html>
