<?php
$host = "localhost";
$user = "root"; // default XAMPP user
$pass = "";      // default XAMPP password = empty
$db   = "library_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
