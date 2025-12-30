<?php 
include("../../config/config.php"); 
include("../../includes/header.php"); 
include("../../includes/navbar.php"); 

if (!isset($_GET['id'])) {
    echo "<script>alert('No book ID provided'); window.location='books.php';</script>";
    exit;
}

$id = $_GET['id'];

// Fetch current book data
$query = "SELECT * FROM books WHERE id = $id";
$result = mysqli_query($conn, $query);
$book = mysqli_fetch_assoc($result);
?>

<div class="container mt-4">
    <h2>Edit Book</h2>

    <form action="" method="POST" class="mt-3">

        <div class="mb-3">
            <label>Book Title</label>
            <input type="text" name="title" class="form-control" value="<?= $book['title']; ?>" required>
        </div>

        <div class="mb-3">
            <label>Author</label>
            <input type="text" name="author" class="form-control" value="<?= $book['author']; ?>">
        </div>

        <div class="mb-3">
            <label>Category</label>
            <input type="text" name="category" class="form-control" value="<?= $book['category']; ?>">
        </div>

        <div class="mb-3">
            <label>ISBN</label>
            <input type="text" name="isbn" class="form-control" value="<?= $book['isbn']; ?>">
        </div>

        <div class="mb-3">
            <label>Add Copies</label>
            <input type="number" name="copies_added" class="form-control" min="1" required>
            <small class="text-muted">This will ADD to the existing total.</small>
        </div>

        <button type="submit" name="update" class="btn btn-warning">Update Book</button>
        <a href="books.php" class="btn btn-secondary">Back</a>

    </form>
</div>

</body>
</html>

<?php
if (isset($_POST['update'])) {

    $title   = $_POST['title'];
    $author  = $_POST['author'];
    $category = $_POST['category'];
    $isbn    = $_POST['isbn'];
    $added   = intval($_POST['copies_added']);

    // Get current totals again (important)
    $q = mysqli_query($conn, "
        SELECT copies_total, copies_available 
        FROM books 
        WHERE id = '$id'
    ");

    $row = mysqli_fetch_assoc($q);

    $old_total = $row['copies_total'];
    $old_available = $row['copies_available'];

    // Calculate borrowed books
    $borrowed = $old_total - $old_available;

    // New totals
    $new_total = $old_total + $added;
    $new_available = $new_total - $borrowed;

    if ($new_available < 0) {
        $new_available = 0;
    }

    $update = "
        UPDATE books SET 
            title='$title', 
            author='$author', 
            category='$category',
            isbn='$isbn',
            copies_total='$new_total',
            copies_available='$new_available'
        WHERE id='$id'
    ";

    if (mysqli_query($conn, $update)) {
        echo "<script>
                alert('Book updated successfully!');
                window.location.href='books.php';
              </script>";
    } else {
        echo "Error updating: " . mysqli_error($conn);
    }
}
?>
