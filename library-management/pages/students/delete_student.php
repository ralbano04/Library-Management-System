<?php 
include("../../config/config.php");

if (!isset($_GET['id'])) {
    echo "<script>alert('Invalid student'); window.location='students.php';</script>";
    exit;
}

$id = $_GET['id'];

$query = "DELETE FROM students WHERE id = $id";

if (mysqli_query($conn, $query)) {
    echo "<script>
            alert('Student deleted successfully!');
            window.location.href='students.php';
          </script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
