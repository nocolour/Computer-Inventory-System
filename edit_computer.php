<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$permissions = json_decode($user['permissions'], true);

// Check if the user has 'edit' permission
if (!$permissions['edit']) {
    echo "You do not have permission to edit computers.";
    exit;
}

// Get the computer details for editing
if (isset($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM computers WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $computer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$computer) {
        echo "Computer not found!";
        exit;
    }
} else {
    echo "No computer ID provided!";
    exit;
}

// Handle form submission to update computer details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
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

    $stmt = $conn->prepare("UPDATE computers SET 
        name = ?, type = ?, serial_number = ?, processor = ?, ram = ?, hard_disk = ?, os = ?, brand = ?, ip_address = ?, mac_address = ?, location = ?, department = ?, existing_user = ?, other_details = ? 
        WHERE id = ?");
    try {
        $stmt->execute([$name, $type, $serial_number, $processor, $ram, $hard_disk, $os, $brand, $ip_address, $mac_address, $location, $department, $existing_user, $other_details, $computer['id']]);
        echo "Computer updated successfully! <a href='dashboard.php'>Go Back</a>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $stmt = $conn->prepare("DELETE FROM computers WHERE id = ?");
    try {
        $stmt->execute([$computer['id']]);
        echo "Computer deleted successfully! <a href='dashboard.php'>Go Back</a>";
        exit; // Stop further execution after deletion
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Computer</title>
</head>
<body>
    <h2>Edit Computer</h2>
    <form method="POST">
        <label>Computer Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($computer['name']); ?>" required><br>
        <label>Type:</label>
        <select name="type" required>
            <option value="Laptop" <?php if ($computer['type'] == 'Laptop') echo 'selected'; ?>>Laptop</option>
            <option value="Desktop" <?php if ($computer['type'] == 'Desktop') echo 'selected'; ?>>Desktop</option>
        </select><br>
        <label>Serial Number:</label>
        <input type="text" name="serial_number" value="<?php echo htmlspecialchars($computer['serial_number']); ?>" required><br>
        <label>Processor:</label>
        <input type="text" name="processor" value="<?php echo htmlspecialchars($computer['processor']); ?>" required><br>
        <label>RAM:</label>
        <input type="text" name="ram" value="<?php echo htmlspecialchars($computer['ram']); ?>" required><br>
        <label>Hard Disk:</label>
        <input type="text" name="hard_disk" value="<?php echo htmlspecialchars($computer['hard_disk']); ?>" required><br>
        <label>Operating System:</label>
        <input type="text" name="os" value="<?php echo htmlspecialchars($computer['os']); ?>" required><br>
        <label>Brand:</label>
        <input type="text" name="brand" value="<?php echo htmlspecialchars($computer['brand']); ?>" required><br>
        <label>IP Address:</label>
        <input type="text" name="ip_address" value="<?php echo htmlspecialchars($computer['ip_address']); ?>" required><br>
        <label>MAC Address:</label>
        <input type="text" name="mac_address" value="<?php echo htmlspecialchars($computer['mac_address']); ?>" required><br>
        <label>Location:</label>
        <input type="text" name="location" value="<?php echo htmlspecialchars($computer['location']); ?>" required><br>
        <label>Department:</label>
        <input type="text" name="department" value="<?php echo htmlspecialchars($computer['department']); ?>" required><br>
        <label>Existing User:</label>
        <input type="text" name="existing_user" value="<?php echo htmlspecialchars($computer['existing_user']); ?>" required><br>
        <label>Other Details:</label>
        <textarea name="other_details"><?php echo htmlspecialchars($computer['other_details']); ?></textarea><br>
        <button type="submit" name="update">Update Computer</button>
    </form>
    <hr>
    <form method="POST">
        <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this computer?');">Delete Computer</button>
    </form>
</body>
</html>
