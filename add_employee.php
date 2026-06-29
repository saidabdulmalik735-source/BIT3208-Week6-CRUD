<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db_connect.php';

$errors = [];
$success = false;

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
    
    // Validation
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
        
        $sql = "INSERT INTO employees (employee_id, full_name, email, phone, department, position, salary, date_hired, gender, address) 
                VALUES ('$eid', '$name', '$em', '$ph', '$dept', '$pos', $salary, '$date_hired', '$gender', '$addr')";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: index.php?msg=added");
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
    <title>Add Employee — EMS</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container">
        <div class="header">
            <h1> Employee Management System</h1>
            <p>Add new employee record</p>
        </div>

        <div class="nav">
            <div class="nav-links">
                <a href="dashboard.php"> Dashboard</a>
                <a href="index.php"> Employees</a>
                <a href="add_employee.php" class="active"> Add Employee</a>
            </div>
            <div class="user-info">
                <span class="user-name">👤 <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>

        <div class="card">
            <h2 style="color: #2c3e50; margin-bottom: 20px;"> Add New Employee</h2>

            <?php if (!empty($errors)): ?>
                <div class="status error">
                    <strong>Please fix the following errors:</strong>
                    <ul style="margin-top: 10px; padding-left: 20px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="add_employee.php" method="post" id="empForm">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Employee ID *</label>
                        <input type="text" name="employee_id" id="employee_id" placeholder="e.g., EMP-006" value="<?php echo isset($_POST['employee_id']) ? htmlspecialchars($_POST['employee_id']) : ''; ?>">
                        <div class="error-msg" id="eidError">Employee ID is required</div>
                    </div>
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="full_name" id="full_name" placeholder="Full name" value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                        <div class="error-msg" id="nameError">Full name is required</div>
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" id="email" placeholder="email@company.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        <div class="error-msg" id="emailError">Valid email is required</div>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" placeholder="07XX XXX XXX" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Department *</label>
                        <select name="department" id="department">
                            <option value="">Select Department</option>
                            <option value="IT" <?php if(isset($_POST['department']) && $_POST['department']=='IT') echo 'selected'; ?>>IT</option>
                            <option value="HR" <?php if(isset($_POST['department']) && $_POST['department']=='HR') echo 'selected'; ?>>HR</option>
                            <option value="Finance" <?php if(isset($_POST['department']) && $_POST['department']=='Finance') echo 'selected'; ?>>Finance</option>
                            <option value="Marketing" <?php if(isset($_POST['department']) && $_POST['department']=='Marketing') echo 'selected'; ?>>Marketing</option>
                        </select>
                        <div class="error-msg" id="deptError">Department is required</div>
                    </div>
                    <div class="form-group">
                        <label>Position</label>
                        <input type="text" name="position" placeholder="Job title" value="<?php echo isset($_POST['position']) ? htmlspecialchars($_POST['position']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Salary (KES) *</label>
                        <input type="number" name="salary" id="salary" step="0.01" min="1" placeholder="50000" value="<?php echo isset($_POST['salary']) ? htmlspecialchars($_POST['salary']) : ''; ?>">
                        <div class="error-msg" id="salaryError">Valid salary is required</div>
                    </div>
                    <div class="form-group">
                        <label>Date Hired</label>
                        <input type="date" name="date_hired" value="<?php echo isset($_POST['date_hired']) ? htmlspecialchars($_POST['date_hired']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender">
                            <option value="">Select</option>
                            <option value="Male" <?php if(isset($_POST['gender']) && $_POST['gender']=='Male') echo 'selected'; ?>>Male</option>
                            <option value="Female" <?php if(isset($_POST['gender']) && $_POST['gender']=='Female') echo 'selected'; ?>>Female</option>
                            <option value="Other" <?php if(isset($_POST['gender']) && $_POST['gender']=='Other') echo 'selected'; ?>>Other</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" style="margin-top: 10px;">
                    <label>Address</label>
                    <textarea name="address" placeholder="Full address..."><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                </div>
                <div style="margin-top: 25px;">
                    <button type="submit" class="btn btn-success"> Save Employee</button>
                    <a href="index.php" class="btn btn-primary" style="margin-left: 10px;">← Cancel</a>
                </div>
            </form>
        </div>

        <div class="footer">
            <p></p>
        </div>
    </div>

    <script>
        document.getElementById('empForm').addEventListener('submit', function(e) {
            let valid = true;
            
            const fields = [
                {id: 'employee_id', err: 'eidError'},
                {id: 'full_name', err: 'nameError'},
                {id: 'email', err: 'emailError'},
                {id: 'department', err: 'deptError'},
                {id: 'salary', err: 'salaryError'}
            ];
            
            fields.forEach(f => {
                const el = document.getElementById(f.id);
                const err = document.getElementById(f.err);
                if (!el.value.trim() || (f.id === 'salary' && parseFloat(el.value) <= 0)) {
                    err.classList.add('show');
                    el.classList.add('error');
                    valid = false;
                } else {
                    err.classList.remove('show');
                    el.classList.remove('error');
                }
            });
            
            if (!valid) e.preventDefault();
        });
    </script>

</body>
</html>
