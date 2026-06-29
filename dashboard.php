<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db_connect.php';

$total = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM employees"));
$it = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM employees WHERE department = 'IT'"));
$hr = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM employees WHERE department = 'HR'"));
$finance = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM employees WHERE department = 'Finance'"));
$marketing = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM employees WHERE department = 'Marketing'"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — EMS</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container">
        <div class="header">
            <h1> Employee Management System</h1>
            <p>Challenge Task — Week 6</p>
        </div>

        <div class="nav">
            <div class="nav-links">
                <a href="dashboard.php" class="active"> Dashboard</a>
                <a href="index.php"> Employees</a>
                <a href="add_employee.php"> Add Employee</a>
            </div>
            <div class="user-info">
                <span class="user-name"> <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total; ?></div>
                <div class="stat-label">Total Employees</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $it; ?></div>
                <div class="stat-label">IT Department</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $hr; ?></div>
                <div class="stat-label">HR Department</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $finance; ?></div>
                <div class="stat-label">Finance</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $marketing; ?></div>
                <div class="stat-label">Marketing</div>
            </div>
        </div>

        <div class="card">
            <h2 style="color: #2c3e50; margin-bottom: 20px;"> Quick Actions</h2>
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="add_employee.php" class="btn btn-success"> Add New Employee</a>
                <a href="index.php" class="btn btn-primary"> View All Employees</a>
            </div>
        </div>

        <div class="footer">
            <p></p>
        </div>
    </div>

</body>
</html>
