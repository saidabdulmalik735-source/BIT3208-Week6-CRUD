<?php
include 'includes/db_connect.php';

$message = "";
$messageClass = "";

// Get student ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

// Fetch existing student
$result = mysqli_query($conn, "SELECT * FROM students WHERE id = $id");
if (mysqli_num_rows($result) != 1) {
    header("Location: index.php");
    exit();
}

$student = mysqli_fetch_assoc($result);

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_number = mysqli_real_escape_string($conn, $_POST['student_number']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    $sql = "UPDATE students SET 
            student_number = '$student_number',
            full_name = '$full_name',
            email = '$email',
            phone = '$phone',
            course = '$course',
            gender = '$gender',
            date_of_birth = '$date_of_birth',
            address = '$address'
            WHERE id = $id";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?msg=updated");
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
    <title>Edit Student — SMS</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container">
        <div class="header">
            <h1> Student Management System</h1>
            <p>Edit student record</p>
        </div>

        <div class="nav">
            <div class="nav-links">
                <a href="index.php"> All Students</a>
                <a href="add_student.php"> Add Student</a>
            </div>
        </div>

        <div class="card">
            <h2 style="color: #2c3e50; margin-bottom: 20px;"> Edit Student: <?php echo htmlspecialchars($student['full_name']); ?></h2>

            <?php if (!empty($message)): ?>
                <div class="status <?php echo $messageClass; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="edit_student.php?id=<?php echo $id; ?>" method="post">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Student Number *</label>
                        <input type="text" name="student_number" value="<?php echo htmlspecialchars($student['student_number']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="full_name" value="<?php echo htmlspecialchars($student['full_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($student['phone']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Course *</label>
                        <select name="course" required>
                            <option value="">Select Course</option>
                            <option value="BIT" <?php if($student['course']=='BIT') echo 'selected'; ?>>BIT</option>
                            <option value="BCS" <?php if($student['course']=='BCS') echo 'selected'; ?>>BCS</option>
                            <option value="BSCIT" <?php if($student['course']=='BSCIT') echo 'selected'; ?>>BSCIT</option>
                            <option value="DIT" <?php if($student['course']=='DIT') echo 'selected'; ?>>DIT</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender">
                            <option value="">Select</option>
                            <option value="Male" <?php if($student['gender']=='Male') echo 'selected'; ?>>Male</option>
                            <option value="Female" <?php if($student['gender']=='Female') echo 'selected'; ?>>Female</option>
                            <option value="Other" <?php if($student['gender']=='Other') echo 'selected'; ?>>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="date_of_birth" value="<?php echo $student['date_of_birth']; ?>">
                    </div>
                </div>
                <div class="form-group" style="margin-top: 10px;">
                    <label>Address</label>
                    <textarea name="address"><?php echo htmlspecialchars($student['address']); ?></textarea>
                </div>
                <div style="margin-top: 25px;">
                    <button type="submit" class="btn btn-success"> Update Student</button>
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
