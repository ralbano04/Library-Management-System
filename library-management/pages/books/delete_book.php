<?php 
include("../../config/config.php");

if (!isset($_GET['id'])) {
    echo "<script>
            alert('Invalid book');
            window.location.href='books.php';
          </script>";
    exit;
}

$id = $_GET['id'];

// CHECK if the book has transactions linked to it
$check = mysqli_query($conn, "SELECT * FROM transactions WHERE book_id = $id");

if (mysqli_num_rows($check) > 0) {
    echo "<script>
            alert('Cannot delete this book. It has existing borrow/return records.');
            window.location.href='books.php';
          </script>";
    exit;
}

// If no transactions, allow deletion
$query = "DELETE FROM books WHERE id = $id";

if (mysqli_query($conn, $query)) {
    echo "<script>
            alert('Book deleted successfully!');
            window.location.href='books.php';
          </script>";
} else {
    echo "Error deleting: " . mysqli_error($conn);
}
?>
