<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $default_permissions = json_encode(["view" => true, "edit" => false, "add" => false]);

    $stmt = $conn->prepare("INSERT INTO users (username, password, role, permissions) VALUES (?, ?, 'user', ?)");
    try {
        $stmt->execute([$username, $hashed_password, $default_permissions]);
        echo "User registered successfully! <a href='login.php'>Login here</a>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
</head>
<body>
    <h2>Signup</h2>
    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
