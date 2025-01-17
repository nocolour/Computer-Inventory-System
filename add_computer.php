<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$permissions = json_decode($user['permissions'], true);

if (!$permissions['add']) {
    echo "You do not have permission to add new computers.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $serial_number = $_POST['serial_number'];
    $processor = $_POST['processor'];
    $ram = $_POST['ram'];
    $hard_disk = $_POST['hard_disk'];
    $os = $_POST['os'];
    $brand = $_POST['brand'];
    $ip_address = $_POST['ip_address'];
    $mac_address = $_POST['mac_address'];
    $location = $_POST['location'];
    $department = $_POST['department'];
    $existing_user = $_POST['existing_user'];
    $other_details = $_POST['other_details'];

    $stmt = $conn->prepare("INSERT INTO computers 
        (name, type, serial_number, processor, ram, hard_disk, os, brand, ip_address, mac_address, location, department, existing_user, other_details) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    try {
        $stmt->execute([$name, $type, $serial_number, $processor, $ram, $hard_disk, $os, $brand, $ip_address, $mac_address, $location, $department, $existing_user, $other_details]);
        echo "Computer added successfully! <a href='dashboard.php'>Go Back</a>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Computer</title>
</head>
<body>
    <h2>Add New Computer</h2>
    <form method="POST">
        <label>Computer Name:</label>
        <input type="text" name="name" required><br>
        <label>Type:</label>
        <select name="type" required>
            <option value="Laptop">Laptop</option>
            <option value="Desktop">Desktop</option>
        </select><br>
        <label>Serial Number:</label>
        <input type="text" name="serial_number" required><br>
        <label>Processor:</label>
        <input type="text" name="processor" required><br>
        <label>RAM:</label>
        <input type="text" name="ram" required><br>
        <label>Hard Disk:</label>
        <input type="text" name="hard_disk" required><br>
        <label>Operating System:</label>
        <input type="text" name="os" required><br>
        <label>Brand:</label>
        <input type="text" name="brand" required><br>
        <label>IP Address:</label>
        <input type="text" name="ip_address" required><br>
        <label>MAC Address:</label>
        <input type="text" name="mac_address" required><br>
        <label>Location:</label>
        <input type="text" name="location" required><br>
        <label>Department:</label>
        <input type="text" name="department" required><br>
        <label>Current User:</label>
        <input type="text" name="existing_user" required><br>
        <label>Other Details:</label>
        <textarea name="other_details"></textarea><br>
        <button type="submit">Add Computer</button>
    </form>
</body>
</html>
