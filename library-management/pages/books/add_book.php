<?php 
include("../../config/config.php"); 
include("../../includes/header.php"); 
include("../../includes/navbar.php"); 
?>

<div class="container mt-4">
    <h2>Add New Book</h2>

    <form action="" method="POST" class="mt-3">

        <div class="mb-3">
            <label>Book Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Author</label>
            <input type="text" name="author" class="form-control">
        </div>

        <div class="mb-3">
            <label>Category</label>
            <input type="text" name="category" class="form-control">
        </div>

        <div class="mb-3">
            <label>ISBN</label>
            <input type="text" name="isbn" class="form-control">
        </div>

        <div class="mb-3">
            <label>Total Copies</label>
            <input type="number" name="copies_total" class="form-control" min="1" value="1" required>
        </div>

        <button type="submit" name="save" class="btn btn-primary">Save Book</button>
        <a href="books.php" class="btn btn-secondary">Back</a>
    </form>
</div>

</body>
</html>

<?php
if (isset($_POST['save'])) {

    $title  = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $isbn = $_POST['isbn'];
    $copies_total = $_POST['copies_total'];

    // available copies = total copies
    $copies_available = $copies_total;

    $query = "INSERT INTO books (title, author, category, isbn, copies_total, copies_available) 
              VALUES ('$title', '$author', '$category', '$isbn', '$copies_total', '$copies_available')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Book added successfully!');
                window.location.href='books.php';
              </script>";
    } 
    else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
