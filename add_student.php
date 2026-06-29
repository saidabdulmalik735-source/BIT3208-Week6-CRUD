<?php
include 'includes/db_connect.php';

$message = "";
$messageClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_number = mysqli_real_escape_string($conn, $_POST['student_number']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    $sql = "INSERT INTO students (student_number, full_name, email, phone, course, gender, date_of_birth, address) 
            VALUES ('$student_number', '$full_name', '$email', '$phone', '$course', '$gender', '$date_of_birth', '$address')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?msg=added");
        exit();
    } else {
        $message = "Error: " . mysqli_error($conn);
        $messageClass = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student — SMS</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container">
        <div class="header">
            <h1> Student Management System</h1>
            <p>Add a new student record</p>
        </div>

        <div class="nav">
            <div class="nav-links">
                <a href="index.php"> All Students</a>
                <a href="add_student.php" class="active"> Add Student</a>
            </div>
        </div>

        <div class="card">
            <h2 style="color: #2c3e50; margin-bottom: 20px;"> Add New Student</h2>

            <?php if (!empty($message)): ?>
                <div class="status <?php echo $messageClass; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="add_student.php" method="post">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Student Number *</label>
                        <input type="text" name="student_number" placeholder="e.g., BIT/2024/001" required>
                    </div>
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="full_name" placeholder="e.g., John Kamau" required>
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" placeholder="student@email.com" required>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" placeholder="07XX XXX XXX">
                    </div>
                    <div class="form-group">
                        <label>Course *</label>
                        <select name="course" required>
                            <option value="">Select Course</option>
                            <option value="BIT">BIT</option>
                            <option value="BCS">BCS</option>
                            <option value="BSCIT">BSCIT</option>
                            <option value="DIT">DIT</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender">
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="date_of_birth">
                    </div>
                </div>
                <div class="form-group" style="margin-top: 10px;">
                    <label>Address</label>
                    <textarea name="address" placeholder="Enter full address..."></textarea>
                </div>
                <div style="margin-top: 25px;">
                    <button type="submit" class="btn btn-success">💾 Save Student</button>
                    <a href="index.php" class="btn btn-primary" style="margin-left: 10px;">← Cancel</a>
                </div>
            </form>
        </div>

        <div class="footer">
            <p>Student Management System</p>
        </div>
    </div>

</body>
</html>
