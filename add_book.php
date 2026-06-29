<?php
include 'includes/db_connect.php';

$message = "";
$messageClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = mysqli_real_escape_string($conn, $_POST['book_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $published_year = intval($_POST['published_year']);
    $is_available = isset($_POST['is_available']) ? 1 : 0;
    
    $sql = "INSERT INTO books (book_id, title, author, category, published_year, is_available) 
            VALUES ('$book_id', '$title', '$author', '$category', $published_year, $is_available)";
    
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
    <title>Add Book — Library</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container">
        <div class="header">
            <h1> Library Book Management</h1>
            <p>Add a new book to the catalog</p>
        </div>

        <div class="nav">
            <div class="nav-links">
                <a href="index.php"> All Books</a>
                <a href="add_book.php" class="active"> Add Book</a>
            </div>
        </div>

        <div class="card">
            <h2 style="color: #2c3e50; margin-bottom: 20px;"> Add New Book</h2>

            <?php if (!empty($message)): ?>
                <div class="status <?php echo $messageClass; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="add_book.php" method="post">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Book ID *</label>
                        <input type="text" name="book_id" placeholder="e.g., LIB-006" required>
                    </div>
                    <div class="form-group">
                        <label>Title *</label>
                        <input type="text" name="title" placeholder="Book title" required>
                    </div>
                    <div class="form-group">
                        <label>Author *</label>
                        <input type="text" name="author" placeholder="Author name" required>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category">
                            <option value="">Select Category</option>
                            <option value="Fiction">Fiction</option>
                            <option value="Technology">Technology</option>
                            <option value="Business">Business</option>
                            <option value="Literature">Literature</option>
                            <option value="Science">Science</option>
                            <option value="History">History</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Published Year</label>
                        <input type="number" name="published_year" placeholder="e.g., 2024" min="1800" max="2030">
                    </div>
                    <div class="form-group" style="display: flex; align-items: center; gap: 10px; padding-top: 35px;">
                        <input type="checkbox" name="is_available" id="is_available" checked style="width: auto;">
                        <label for="is_available" style="margin: 0;">Available for borrowing</label>
                    </div>
                </div>
                <div style="margin-top: 25px;">
                    <button type="submit" class="btn btn-success"> Save Book</button>
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
