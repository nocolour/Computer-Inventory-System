<?php
include 'db.php';
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Access Denied: You are not an admin.");
}

// Fetch all users
$stmt = $conn->query("SELECT id, username, role, permissions FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle permission updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_permissions'])) {
    $user_id = $_POST['user_id'];
    $view_permission = isset($_POST['view']) ? true : false;
    $edit_permission = isset($_POST['edit']) ? true : false;
    $add_permission = isset($_POST['add']) ? true : false;

    $permissions = json_encode([
        "view" => $view_permission,
        "edit" => $edit_permission,
        "add" => $add_permission,
    ]);

    $stmt = $conn->prepare("UPDATE users SET permissions = ? WHERE id = ?");
    try {
        $stmt->execute([$permissions, $user_id]);
        echo "<p>Permissions updated successfully for user ID $user_id.</p>";
    } catch (PDOException $e) {
        echo "<p>Error updating permissions: " . $e->getMessage() . "</p>";
    }
}

// Handle password reset
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    $user_id = $_POST['user_id'];
    $new_password = $_POST['new_password'];

    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        try {
            $stmt->execute([$hashed_password, $user_id]);
            echo "<p>Password updated successfully for user ID $user_id.</p>";
        } catch (PDOException $e) {
            echo "<p>Error updating password: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>Error: Password cannot be empty!</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <p><a href="dashboard.php">Return Back to Dashboard</a></p>

    <h2>Admin Dashboard</h2>
    <a href="logout.php">Logout</a>
    <h3>Manage Users</h3>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Permissions</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?php echo $u['id']; ?></td>
                    <td><?php echo htmlspecialchars($u['username']); ?></td>
                    <td><?php echo htmlspecialchars($u['role']); ?></td>
                    <td>
                        <?php
                        $permissions = json_decode($u['permissions'], true);
                        echo "View: " . ($permissions['view'] ? 'Yes' : 'No') . ", ";
                        echo "Edit: " . ($permissions['edit'] ? 'Yes' : 'No') . ", ";
                        echo "Add: " . ($permissions['add'] ? 'Yes' : 'No');
                        ?>
                    </td>
                    <td>
                        <!-- Form to update permissions -->
                        <form method="POST" style="display: inline-block;">
                            <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                            <label><input type="checkbox" name="view" <?php echo $permissions['view'] ? 'checked' : ''; ?>> View</label>
                            <label><input type="checkbox" name="edit" <?php echo $permissions['edit'] ? 'checked' : ''; ?>> Edit</label>
                            <label><input type="checkbox" name="add" <?php echo $permissions['add'] ? 'checked' : ''; ?>> Add</label>
                            <button type="submit" name="update_permissions">Update Permissions</button>
                        </form>

                        <!-- Form to reset password -->
                        <form method="POST" style="display: inline-block; margin-left: 10px;">
                            <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                            <label>New Password:</label>
                            <input type="password" name="new_password" required>
                            <button type="submit" name="reset_password">Reset Password</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
