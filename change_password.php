<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the new password matches the confirmation password
    if ($new_password !== $confirm_password) {
        echo "<p style='color: red;'>Error: New password and confirm password do not match!</p>";
    } else {
        // Fetch the current user data from the database
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user['id']]);
        $current_user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the current password
        if (password_verify($current_password, $current_user['password'])) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            try {
                $stmt->execute([$hashed_password, $user['id']]);
                echo "<p style='color: green;'>Password updated successfully!</p>";
            } catch (PDOException $e) {
                echo "<p style='color: red;'>Error updating password: " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p style='color: red;'>Error: Current password is incorrect!</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
</head>
<body>
    <p><a href="dashboard.php">Return Back to Dashboard</a></p>

    <h2>Change Your Password</h2>
    <a href="dashboard.php">Return to Dashboard</a>
    <form method="POST">
        <label>Current Password:</label>
        <input type="password" name="current_password" required><br>
        <label>New Password:</label>
        <input type="password" name="new_password" required><br>
        <label>Confirm New Password:</label>
        <input type="password" name="confirm_password" required><br>
        <button type="submit">Change Password</button>
    </form>
</body>
</html>
