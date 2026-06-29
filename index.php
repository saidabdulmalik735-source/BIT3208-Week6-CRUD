<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db_connect.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$dept_filter = isset($_GET['dept']) ? mysqli_real_escape_string($conn, $_GET['dept']) : '';

// Build query with search
$sql = "SELECT * FROM employees WHERE 1=1";
if (!empty($search)) {
    $sql .= " AND (full_name LIKE '%$search%' OR email LIKE '%$search%' OR employee_id LIKE '%$search%' OR department LIKE '%$search%')";
}
if (!empty($dept_filter)) {
    $sql .= " AND department = '$dept_filter'";
}
$sql .= " ORDER BY id DESC";

$result = mysqli_query($conn, $sql);
$total = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees — EMS</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container">
        <div class="header">
            <h1> Employee Management System</h1>
            <p>Search, view, and manage employee records</p>
        </div>

        <div class="nav">
            <div class="nav-links">
                <a href="dashboard.php"> Dashboard</a>
                <a href="index.php" class="active"> Employees</a>
                <a href="add_employee.php"> Add Employee</a>
            </div>
            <div class="user-info">
                <span class="user-name">👤 <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>

        <!-- Search Section -->
        <div class="search-section">
            <form action="index.php" method="get" style="display: flex; gap: 15px; flex: 1; flex-wrap: wrap;">
                <input type="text" name="search" class="search-input" placeholder=" Search by name, email, ID, or department..." value="<?php echo htmlspecialchars($search); ?>">
                <select name="dept" style="padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px;">
                    <option value="">All Departments</option>
                    <option value="IT" <?php if($dept_filter=='IT') echo 'selected'; ?>>IT</option>
                    <option value="HR" <?php if($dept_filter=='HR') echo 'selected'; ?>>HR</option>
                    <option value="Finance" <?php if($dept_filter=='Finance') echo 'selected'; ?>>Finance</option>
                    <option value="Marketing" <?php if($dept_filter=='Marketing') echo 'selected'; ?>>Marketing</option>
                </select>
                <button type="submit" class="search-btn">Search</button>
                <?php if (!empty($search) || !empty($dept_filter)): ?>
                    <a href="index.php" class="btn btn-danger" style="padding: 12px 20px;">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="card">
            <h2 style="color: #2c3e50; margin-bottom: 10px;"> Employee Records</h2>
            <p style="color: #7f8c8d; margin-bottom: 20px;"><?php echo $total; ?> employee(s) found</p>

            <?php if (isset($_GET['msg'])): ?>
                <?php if ($_GET['msg'] == 'deleted'): ?>
                    <div class="status success"> Employee deleted successfully.</div>
                <?php elseif ($_GET['msg'] == 'added'): ?>
                    <div class="status success"> Employee added successfully.</div>
                <?php elseif ($_GET['msg'] == 'updated'): ?>
                    <div class="status success"> Employee updated successfully.</div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($total > 0): ?>
                <div style="overflow-x: auto;">
                    <table class="crud-table">
                        <thead>
                            <tr>
                                <th>Emp ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Salary</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($row['employee_id']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td>
                                        <?php 
                                        $dept_class = 'badge-it';
                                        if ($row['department'] == 'HR') $dept_class = 'badge-hr';
                                        elseif ($row['department'] == 'Finance') $dept_class = 'badge-finance';
                                        elseif ($row['department'] == 'Marketing') $dept_class = 'badge-marketing';
                                        ?>
                                        <span class="badge <?php echo $dept_class; ?>"><?php echo $row['department']; ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['position']); ?></td>
                                    <td class="salary">KES <?php echo number_format($row['salary'], 2); ?></td>
                                    <td>
                                        <a href="edit_employee.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-small"> Edit</a>
                                        <a href="delete_employee.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-small" onclick="return confirm('Delete <?php echo $row['full_name']; ?>?')"> Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <h3> No employees found</h3>
                    <p><?php echo !empty($search) ? 'Try different search terms.' : 'Add your first employee.'; ?></p>
                    <a href="add_employee.php" class="btn btn-primary" style="margin-top: 15px;">Add Employee</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="footer">
            <p></p>
        </div>
    </div>

</body>
</html>
