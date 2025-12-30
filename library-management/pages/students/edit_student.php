<?php 
include("../../config/config.php"); 
include("../../includes/header.php"); 
include("../../includes/navbar.php"); 

if (!isset($_GET['id'])) {
    echo "<script>alert('Invalid student'); window.location='students.php';</script>";
    exit;
}

$id = $_GET['id'];
$query = "SELECT * FROM students WHERE id = $id";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);

if (!$student) {
    echo "<script>alert('Student not found'); window.location='students.php';</script>";
    exit;
}
?>

<div class="container mt-4">
    <h2>Edit Student</h2>

    <form action="" method="POST" class="mt-3">

        <!-- Student Number -->
        <div class="mb-3">
            <label>Student Number</label>
            <input type="text" name="student_number" class="form-control" 
                   value="<?= $student['student_number']; ?>" required>
        </div>

        <!-- Name -->
        <div class="mb-3">
            <label>Full Name</label>
            <input type="text" name="name" class="form-control" 
                   value="<?= $student['name']; ?>" required>
        </div>

        <!-- Course -->
        <div class="mb-3">
            <label>Course</label>
            <select name="course" class="form-control" required>
                <option value="">Select Course</option>

                <?php 
                $courses = [
                    "BSIT", "BSCS", "BSIS", 
                    "BSED English", "BSED Math", "BEED",
                    "BSBA", "BSA", 
                    "BS Nursing", 
                    "BSPsych"
                ];

                foreach ($courses as $c) {
                    $selected = ($student['course'] == $c) ? "selected" : "";
                    echo "<option value='$c' $selected>$c</option>";
                }
                ?>
            </select>
        </div>

        <!-- Year Level -->
        <div class="mb-3">
            <label>Year Level</label>
            <select name="year_level" class="form-control" required>
                <option value="">Select Year Level</option>

                <?php 
                for ($i = 1; $i <= 5; $i++) {
                    $selected = ($student['year_level'] == $i) ? "selected" : "";
                    echo "<option value='$i' $selected>{$i} Year</option>";
                }
                ?>
            </select>
        </div>

        <!-- Buttons -->
        <button type="submit" name="update" class="btn btn-warning">Update Student</button>
        <a href="students.php" class="btn btn-secondary">Back</a>

    </form>
</div>

</body>
</html>

<?php
// HANDLE UPDATE ACTION
if (isset($_POST['update'])) {

    $student_number = $_POST['student_number'];
    $name = $_POST['name'];
    $course = $_POST['course'];
    $year_level = $_POST['year_level'];

    $update = "UPDATE students SET 
                student_number='$student_number',
                name='$name',
                course='$course',
                year_level='$year_level'
               WHERE id='$id'";

    if (mysqli_query($conn, $update)) {
        echo "<script>
                alert('Student updated successfully!');
                window.location.href='students.php';
              </script>";
    } else {
        echo "Error updating student: " . mysqli_error($conn);
    }
}
?>
