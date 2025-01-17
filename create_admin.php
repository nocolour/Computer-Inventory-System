<?php
include 'db.php';
session_start();

// Check if there is already an admin account in the system
$stmt = $conn->prepare("SELECT COUNT(*) AS admin_count FROM users WHERE role = 'admin'");
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// If an admin account exists, deny access
if ($result['admin_count'] > 0) {
    die("Access Denied: An admin account already exists in the system.");
}

// Handle form submission to create the admin account
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $permissions = json_encode(["view" => true, "edit" => true, "add" => true]);

    $stmt = $conn->prepare("INSERT INTO users (username, password, role, permissions) VALUES (?, ?, 'admin', ?)");
    try {
        $stmt->execute([$username, $hashed_password, $permissions]);
        echo "Admin account created successfully! <a href='login.php'>Go to Login</a>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Admin Account</title>
</head>
<body>
    <h2>Create Admin Account</h2>
    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Create Admin</button>
    </form>
</body>
</html>
