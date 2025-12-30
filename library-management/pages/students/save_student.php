<?php
include("../../config/config.php");

if (isset($_POST['save'])) {

    $student_number = $_POST['student_number'];
    $name           = $_POST['name'];
    $course         = $_POST['course'];
    $year_level     = $_POST['year_level'];

    // CHECK IF STUDENT NUMBER ALREADY EXISTS
    $check = mysqli_query($conn, 
        "SELECT * FROM students WHERE student_number = '$student_number' LIMIT 1"
    );

    if (mysqli_num_rows($check) > 0) {
        echo "<script>
                alert('ID number is already in the system. Please use another ID.');
                window.history.back();
              </script>";
        exit;
    }

    // INSERT NEW STUDENT
    $query = "
        INSERT INTO students (student_number, name, course, year_level)
        VALUES ('$student_number', '$name', '$course', '$year_level')
    ";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Student added successfully!');
                window.location.href='students.php';
              </script>";
    } 
    else {
        echo "<script>
                alert('An error occurred while saving the student.');
              </script>";
    }
}
?>
