<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user']; // Logged-in user details

// Get search, filter, sorting, and pagination parameters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$type_filter = isset($_GET['type_filter']) ? $_GET['type_filter'] : '';
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Allowed columns for sorting and searching
$columns = ['id', 'name', 'type', 'serial_number', 'processor', 'ram', 'hard_disk', 'os', 'brand', 'ip_address', 'mac_address', 'location', 'department', 'existing_user', 'other_details', 'date_added'];

// Validate `sort_by` parameter
if (!in_array($sort_by, $columns)) {
    $sort_by = 'id'; // Default to `id` if an invalid column is provided
}

// Build the SQL query with search, filter, sorting, and pagination
$sql = "SELECT * FROM computers WHERE 1";

// Add search across all columns
if (!empty($search)) {
    $search_conditions = [];
    foreach ($columns as $column) {
        $search_conditions[] = "$column LIKE :search";
    }
    $sql .= " AND (" . implode(" OR ", $search_conditions) . ")";
}

// Add filtering by type
if (!empty($type_filter)) {
    $sql .= " AND type = :type_filter";
}

$sql .= " ORDER BY $sort_by $sort_order LIMIT :limit OFFSET :offset";

$stmt = $conn->prepare($sql);

// Bind parameters
if (!empty($search)) {
    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
}
if (!empty($type_filter)) {
    $stmt->bindValue(':type_filter', $type_filter, PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$computers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total record count for pagination
$count_sql = "SELECT COUNT(*) FROM computers WHERE 1";

// Add search conditions to count query
if (!empty($search)) {
    $search_conditions = [];
    foreach ($columns as $column) {
        $search_conditions[] = "$column LIKE :search";
    }
    $count_sql .= " AND (" . implode(" OR ", $search_conditions) . ")";
}

// Add filtering to count query
if (!empty($type_filter)) {
    $count_sql .= " AND type = :type_filter";
}

$count_stmt = $conn->prepare($count_sql);

// Bind parameters for count query
if (!empty($search)) {
    $count_stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
}
if (!empty($type_filter)) {
    $count_stmt->bindValue(':type_filter', $type_filter, PDO::PARAM_STR);
}

$count_stmt->execute();
$total_records = $count_stmt->fetchColumn();
$total_pages = ceil($total_records / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printable, #printable * {
                visibility: visible;
            }
            #printable {
                width: 100%;
                border-collapse: collapse;
            }
            #printable th, #printable td {
                border: 1px solid black;
                padding: 8px;
                text-align: left;
            }
        }
    </style>
    <script>
        function printReport() {
            window.print(); // Opens the print dialog
        }
    </script>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
    <a href="logout.php">Logout</a>
    <a href="change_password.php">Change Your Password</a>

    <!-- Show Admin Link if the username is 'admin' -->
    <?php if (strtolower($user['username']) === 'admin'): ?>
        <a href="admin.php">Go to Admin Dashboard</a>
    <?php endif; ?>

    <br><br>

    <!-- Add New Computer Button -->
    <a href="add_computer.php" style="padding: 8px 12px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px;">Add New Computer</a>

    <br><br>

    <!-- Search, Filter, and Sorting Form -->
    <form method="GET">
        <label>Search:</label>
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search across all columns">

        <label>Filter by Type:</label>
        <select name="type_filter">
            <option value="">All</option>
            <option value="Laptop" <?php if ($type_filter === 'Laptop') echo 'selected'; ?>>Laptop</option>
            <option value="Desktop" <?php if ($type_filter === 'Desktop') echo 'selected'; ?>>Desktop</option>
        </select>

        <button type="submit">Apply</button>
        <a href="dashboard.php">Reset</a> <!-- Reset Filters -->
    </form>

    <!-- Print Button -->
    <button onclick="printReport()">Print Report</button>

    <h3>Computer Inventory</h3>

    <table border="1" id="printable">
        <thead>
            <tr>
                <?php foreach ($columns as $column): ?>
                    <th>
                        <a href="?sort_by=<?php echo $column; ?>&sort_order=<?php echo ($sort_by === $column && $sort_order === 'ASC') ? 'DESC' : 'ASC'; ?>">
                            <?php echo ucfirst(str_replace('_', ' ', $column)); ?>
                        </a>
                    </th>
                <?php endforeach; ?>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($computers)): ?>
                <?php foreach ($computers as $computer): ?>
                    <tr>
                        <?php foreach ($columns as $column): ?>
                            <td><?php echo htmlspecialchars($computer[$column]); ?></td>
                        <?php endforeach; ?>
                        <td>
                            <a href="edit_computer.php?id=<?php echo $computer['id']; ?>" style="padding: 4px 8px; background: #FFC107; color: white; text-decoration: none; border-radius: 4px;">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?php echo count($columns) + 1; ?>">No results found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div>
        <?php if ($total_pages > 1): ?>
            <p>Page <?php echo $page; ?> of <?php echo $total_pages; ?></p>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>&type_filter=<?php echo htmlspecialchars($type_filter); ?>&sort_by=<?php echo htmlspecialchars($sort_by); ?>&sort_order=<?php echo htmlspecialchars($sort_order); ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        <?php endif; ?>
    </div>
</body>
</html>
