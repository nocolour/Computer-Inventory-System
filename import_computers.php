<?php
require 'vendor/autoload.php'; // Load PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\IOFactory;

include 'db.php';
session_start();

// Ensure only admins can access this page
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Access Denied: You must be an admin to access this page.");
}

$errors = [];
$success_count = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['excel_file']['tmp_name'];

        try {
            // Load the Excel file
            $spreadsheet = IOFactory::load($file_tmp_path);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Process each row
            foreach ($rows as $index => $row) {
                // Skip the header row (index 0)
                if ($index === 0) continue;

                // Extract row data
                $name = $row[0];
                $type = $row[1];
                $serial_number = $row[2];
                $processor = $row[3];
                $ram = $row[4];
                $hard_disk = $row[5];
                $os = $row[6];
                $brand = $row[7];
                $ip_address = $row[8];
                $mac_address = $row[9];
                $location = $row[10];
                $department = $row[11];
                $existing_user = $row[12];
                $other_details = $row[13];

                // Validate MAC address (skip duplicates)
                $stmt = $conn->prepare("SELECT COUNT(*) FROM computers WHERE mac_address = ?");
                $stmt->execute([$mac_address]);
                $mac_exists = $stmt->fetchColumn() > 0;

                if ($mac_exists) {
                    $errors[] = "Duplicate MAC Address found: $mac_address (Row $index)";
                    continue;
                }

                // Insert the row into the database
                $stmt = $conn->prepare("INSERT INTO computers 
                    (name, type, serial_number, processor, ram, hard_disk, os, brand, ip_address, mac_address, location, department, existing_user, other_details) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $type, $serial_number, $processor, $ram, $hard_disk, $os, $brand, $ip_address, $mac_address, $location, $department, $existing_user, $other_details]);

                $success_count++;
            }
        } catch (Exception $e) {
            die("Error reading the Excel file: " . $e->getMessage());
        }
    } else {
        $errors[] = "Please upload a valid Excel file.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Import Computers</title>
</head>
<body>
    <h2>Import Computers from Excel</h2>
    <a href="admin.php">Go Back to Admin Dashboard</a>
    <form method="POST" enctype="multipart/form-data">
        <label>Select Excel File:</label>
        <input type="file" name="excel_file" required><br>
        <button type="submit">Import</button>
    </form>

    <?php if (!empty($errors)): ?>
        <h3>Errors:</h3>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if ($success_count > 0): ?>
        <p>Successfully imported <?php echo $success_count; ?> computers!</p>
    <?php endif; ?>
</body>
</html>
