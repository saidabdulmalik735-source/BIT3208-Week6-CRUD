<?php
include 'includes/db_connect.php';

$message = "";
$messageClass = "";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

$result = mysqli_query($conn, "SELECT * FROM books WHERE id = $id");
if (mysqli_num_rows($result) != 1) {
    header("Location: index.php");
    exit();
}

$book = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = mysqli_real_escape_string($conn, $_POST['book_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $published_year = intval($_POST['published_year']);
    $is_available = isset($_POST['is_available']) ? 1 : 0;
    
    $sql = "UPDATE books SET 
            book_id = '$book_id',
            title = '$title',
            author = '$author',
            category = '$category',
            published_year = $published_year,
            is_available = $is_available
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
    <title>Edit Book — Library</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container">
        <div class="header">
            <h1> Library Book Management</h1>
            <p>Edit book record</p>
        </div>

        <div class="nav">
            <div class="nav-links">
                <a href="index.php"> All Books</a>
                <a href="add_book.php"> Add Book</a>
            </div>
        </div>

        <div class="card">
            <h2 style="color: #2c3e50; margin-bottom: 20px;"> Edit Book: <?php echo htmlspecialchars($book['title']); ?></h2>

            <?php if (!empty($message)): ?>
                <div class="status <?php echo $messageClass; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="edit_book.php?id=<?php echo $id; ?>" method="post">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Book ID *</label>
                        <input type="text" name="book_id" value="<?php echo htmlspecialchars($book['book_id']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Title *</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Author *</label>
                        <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category">
                            <option value="">Select Category</option>
                            <option value="Fiction" <?php if($book['category']=='Fiction') echo 'selected'; ?>>Fiction</option>
                            <option value="Technology" <?php if($book['category']=='Technology') echo 'selected'; ?>>Technology</option>
                            <option value="Business" <?php if($book['category']=='Business') echo 'selected'; ?>>Business</option>
                            <option value="Literature" <?php if($book['category']=='Literature') echo 'selected'; ?>>Literature</option>
                            <option value="Science" <?php if($book['category']=='Science') echo 'selected'; ?>>Science</option>
                            <option value="History" <?php if($book['category']=='History') echo 'selected'; ?>>History</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Published Year</label>
                        <input type="number" name="published_year" value="<?php echo $book['published_year']; ?>" min="1800" max="2030">
                    </div>
                    <div class="form-group" style="display: flex; align-items: center; gap: 10px; padding-top: 35px;">
                        <input type="checkbox" name="is_available" id="is_available" <?php if($book['is_available']) echo 'checked'; ?> style="width: auto;">
                        <label for="is_available" style="margin: 0;">Available for borrowing</label>
                    </div>
                </div>
                <div style="margin-top: 25px;">
                    <button type="submit" class="btn btn-success"> Update Book</button>
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
