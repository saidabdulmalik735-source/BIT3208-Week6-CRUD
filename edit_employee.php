<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db_connect.php';

$errors = [];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

$result = mysqli_query($conn, "SELECT * FROM employees WHERE id = $id");
if (mysqli_num_rows($result) != 1) {
    header("Location: index.php");
    exit();
}

$emp = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = trim($_POST['employee_id']);
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $department = $_POST['department'];
    $position = trim($_POST['position']);
    $salary = floatval($_POST['salary']);
    $date_hired = $_POST['date_hired'];
    $gender = $_POST['gender'];
    $address = trim($_POST['address']);
    
    if (empty($employee_id)) $errors[] = "Employee ID is required";
    if (empty($full_name)) $errors[] = "Full name is required";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
    if (empty($department)) $errors[] = "Department is required";
    if ($salary <= 0) $errors[] = "Salary must be greater than 0";
    
    if (empty($errors)) {
        $eid = mysqli_real_escape_string($conn, $employee_id);
        $name = mysqli_real_escape_string($conn, $full_name);
        $em = mysqli_real_escape_string($conn, $email);
        $ph = mysqli_real_escape_string($conn, $phone);
        $dept = mysqli_real_escape_string($conn, $department);
        $pos = mysqli_real_escape_string($conn, $position);
        $addr = mysqli_real_escape_string($conn, $address);
        
        $sql = "UPDATE employees SET 
                employee_id = '$eid', full_name = '$name', email = '$em', phone = '$ph',
                department = '$dept', position = '$pos', salary = $salary,
                date_hired = '$date_hired', gender = '$gender', address = '$addr'
                WHERE id = $id";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: index.php?msg=updated");
            exit();
        } else {
            $errors[] = "Database error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee — EMS</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container">
        <div class="header">
            <h1> Employee Management System</h1>
            <p>Edit employee record</p>
        </div>

        <div class="nav">
            <div class="nav-links">
                <a href="dashboard.php"> Dashboard</a>
                <a href="index.php"> Employees</a>
                <a href="add_employee.php"> Add Employee</a>
            </div>
            <div class="user-info">
                <span class="user-name">👤 <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>

        <div class="card">
            <h2 style="color: #2c3e50; margin-bottom: 10px;"> Edit Employee</h2>
            <p style="color: #7f8c8d; margin-bottom: 25px;"><?php echo htmlspecialchars($emp['full_name']); ?> (<?php echo $emp['employee_id']; ?>)</p>

            <?php if (!empty($errors)): ?>
                <div class="status error">
                    <strong>Please fix errors:</strong>
                    <ul style="margin-top: 10px; padding-left: 20px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="edit_employee.php?id=<?php echo $id; ?>" method="post">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Employee ID *</label>
                        <input type="text" name="employee_id" value="<?php echo htmlspecialchars($emp['employee_id']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="full_name" value="<?php echo htmlspecialchars($emp['full_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($emp['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($emp['phone']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Department *</label>
                        <select name="department" required>
                            <option value="">Select</option>
                            <option value="IT" <?php if($emp['department']=='IT') echo 'selected'; ?>>IT</option>
                            <option value="HR" <?php if($emp['department']=='HR') echo 'selected'; ?>>HR</option>
                            <option value="Finance" <?php if($emp['department']=='Finance') echo 'selected'; ?>>Finance</option>
                            <option value="Marketing" <?php if($emp['department']=='Marketing') echo 'selected'; ?>>Marketing</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Position</label>
                        <input type="text" name="position" value="<?php echo htmlspecialchars($emp['position']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Salary (KES) *</label>
                        <input type="number" name="salary" step="0.01" min="1" value="<?php echo $emp['salary']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Date Hired</label>
                        <input type="date" name="date_hired" value="<?php echo $emp['date_hired']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender">
                            <option value="">Select</option>
                            <option value="Male" <?php if($emp['gender']=='Male') echo 'selected'; ?>>Male</option>
                            <option value="Female" <?php if($emp['gender']=='Female') echo 'selected'; ?>>Female</option>
                            <option value="Other" <?php if($emp['gender']=='Other') echo 'selected'; ?>>Other</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" style="margin-top: 10px;">
                    <label>Address</label>
                    <textarea name="address"><?php echo htmlspecialchars($emp['address']); ?></textarea>
                </div>
                <div style="margin-top: 25px;">
                    <button type="submit" class="btn btn-success"> Update Employee</button>
                    <a href="index.php" class="btn btn-primary" style="margin-left: 10px;">← Cancel</a>
                </div>
            </form>
        </div>

        <div class="footer">
            <p></p>
        </div>
    </div>

</body>
</html>
