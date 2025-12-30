<?php 
include("../../config/config.php"); 
include("../../includes/header.php"); 
include("../../includes/navbar.php"); 
?>

<div class="container mt-4">
    <h2>Add New Student</h2>

    <form action="save_student.php" method="POST">
        
        <div class="mb-3">
            <label>Student Number</label>
            <input type="text" name="student_number" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Full Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Course</label>
            <select name="course" class="form-control" required>
                <option disabled selected>Select Course</option>

                <option value="BSIT">BSIT</option>
                <option value="BSCS">BSCS</option>
                <option value="BSIS">BSIS</option>

                <option value="BSED English">BSED English</option>
                <option value="BSED Math">BSED Math</option>

                <option value="BEED">BEED</option>
                <option value="BSBA">BSBA</option>
                <option value="BSA">BSA</option>

                <option value="BS Nursing">BS Nursing</option>
                <option value="BSCrim">BSCrim</option>
                <option value="BSPsych">BSPsych</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Year Level</label>
            <select name="year_level" class="form-control" required>
                <option disabled selected>Select Year Level</option>
                <option value="1">1st Year</option>
                <option value="2">2nd Year</option>
                <option value="3">3rd Year</option>
                <option value="4">4th Year</option>
                <option value="5">5th Year</option>
            </select>
        </div>

        <button type="submit" name="save" class="btn btn-success">Save Student</button>
        <a href="students.php" class="btn btn-secondary">Back</a>

    </form>
</div>

</body>
</html>
