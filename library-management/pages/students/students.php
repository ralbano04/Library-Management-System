<?php 
include("../../config/config.php"); 
include("../../includes/header.php"); 
include("../../includes/navbar.php"); 

// GET FILTERS
$search        = $_GET['search'] ?? "";
$filter_course = $_GET['course'] ?? "";
$filter_year   = $_GET['year_level'] ?? "";

// SORTING
$sort  = $_GET['sort'] ?? "id";
$order = $_GET['order'] ?? "ASC";

$allowed_sort = ["id", "student_number", "name", "course", "year_level"];
if (!in_array($sort, $allowed_sort)) {
    $sort = "id";
}

// PAGINATION
$limit  = 10; 
$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page   = max($page, 1);
$offset = ($page - 1) * $limit;

// WHERE CLAUSE
$where = [];
if ($search !== "") {
    $where[] = "(student_number LIKE '%$search%' OR name LIKE '%$search%')";
}
if ($filter_course !== "") {
    $where[] = "course = '$filter_course'";
}
if ($filter_year !== "") {
    $where[] = "year_level = '$filter_year'";
}
$whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

// COUNT
$count_query  = "SELECT COUNT(*) AS total FROM students $whereSQL";
$count_result = mysqli_query($conn, $count_query);
$total_rows   = mysqli_fetch_assoc($count_result)['total'];
$total_pages  = ceil($total_rows / $limit);

// MAIN QUERY
$query = "
    SELECT * FROM students
    $whereSQL
    ORDER BY $sort $order
    LIMIT $limit OFFSET $offset
";
$result = mysqli_query($conn, $query);
?>

<div class="container mt-4">
    <h2 class="mb-3">Students</h2>

    <!-- ACTION BUTTONS -->
    <div class="mb-3 d-flex gap-2">
        <a href="add_student.php" class="btn btn-success">Add New Student</a>
        <a href="../dashboard.php" class="btn btn-secondary">Back</a>
    </div>

    <!-- FILTER FORM -->
    <form method="GET" class="row g-3 mb-4 align-items-end">

        <div class="col-md-3">
            <label>Search Student</label>
            <input type="text"
                   name="search"
                   value="<?= htmlspecialchars($search) ?>"
                   class="form-control"
                   placeholder="Search name or student number">
        </div>

        <div class="col-md-3">
            <label>Course</label>
            <select name="course" class="form-control">
                <option value="">All</option>
                <?php
                $courses = [
                    "BSIT","BSCS","BSIS","BSED English","BSED Math",
                    "BEED","BSBA","BSA","BS Nursing","BSCrim","BSPsych"
                ];
                foreach ($courses as $c):
                ?>
                    <option value="<?= $c ?>" <?= ($filter_course === $c) ? "selected" : "" ?>>
                        <?= $c ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-2">
            <label>Year Level</label>
            <select name="year_level" class="form-control">
                <option value="">All</option>
                <?php for ($y = 1; $y <= 5; $y++): ?>
                    <option value="<?= $y ?>" <?= ($filter_year == $y) ? "selected" : "" ?>>
                        <?= $y ?><?= $y == 1 ? "st" : ($y == 2 ? "nd" : ($y == 3 ? "rd" : "th")) ?> Year
                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <!-- A–Z / Z–A -->
        <div class="col-md-2 d-flex gap-2">
            <a href="?<?= http_build_query(array_merge($_GET, ['sort'=>'name','order'=>'ASC','page'=>1])) ?>"
               class="btn btn-outline-primary w-50">A–Z</a>

            <a href="?<?= http_build_query(array_merge($_GET, ['sort'=>'name','order'=>'DESC','page'=>1])) ?>"
               class="btn btn-outline-primary w-50">Z–A</a>
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button class="btn btn-warning w-50">Apply</button>
            <a href="students.php" class="btn btn-dark w-50">Clear</a>
        </div>
    </form>

    <!-- STUDENTS TABLE -->
     <div class="table-wrapper">
    <table class="table table-bordered table-striped table-hover students-table">
        <thead class="table-dark">
            <tr>
                <th>No.</th>
                <th>Student No.</th>
                <th>Name</th>
                <th>Course</th>
                <th>Year</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
        <?php 
        $number = $offset + 1;
        while ($row = mysqli_fetch_assoc($result)):
        ?>
            <tr>
                <td><?= $number++; ?></td>
                <td><?= $row['student_number']; ?></td>
                <td><?= $row['name']; ?></td>
                <td><?= $row['course']; ?></td>
                <td><?= $row['year_level']; ?></td>
                <td>
                    <div class="action-buttons">
                        <a href="edit_student.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_student.php?id=<?= $row['id']; ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this student?');">
                           Delete
                        </a>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    </div>
    <!-- PAGINATION -->
    <nav class="d-flex justify-content-center mt-1">
        <ul class="pagination custom-pagination">

            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                <a class="page-link"
                   href="?<?= http_build_query(array_merge($_GET, ['page'=>$page-1])) ?>">
                   Previous
                </a>
            </li>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                    <a class="page-link"
                       href="?<?= http_build_query(array_merge($_GET, ['page'=>$i])) ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>

            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                <a class="page-link"
                   href="?<?= http_build_query(array_merge($_GET, ['page'=>$page+1])) ?>">
                   Next
                </a>
            </li>

        </ul>
    </nav>
</div>

<!-- STYLES -->
<style>
.students-table {
    table-layout: fixed;
    width: 100%;
}

.students-table th:nth-child(1),
.students-table td:nth-child(1) { width: 60px; }

.students-table th:nth-child(2),
.students-table td:nth-child(2) { width: 150px; }

.students-table th:nth-child(3),
.students-table td:nth-child(3) { width: 280px; }

.students-table th:nth-child(4),
.students-table td:nth-child(4) { width: 150px; }

.students-table th:nth-child(5),
.students-table td:nth-child(5) { width: 80px; }

.students-table th:nth-child(6),
.students-table td:nth-child(6) { width: 160px; }

.action-buttons {
    display: flex;
    gap: 6px;
}

.custom-pagination .page-link {
    background-color: #f1f3f5;
    color: #000;
    border-radius: 6px;
}

.custom-pagination .page-item.active .page-link {
    background-color: #0d6efd;
    color: #fff;
}
/* Center table column headers */
table thead th {
    text-align: center;
    vertical-align: middle;
}
/* ===============================
   FIX TABLE HEIGHT (NO JUMPING)
=============================== */
.table-wrapper {
    min-height: 520px;   /* adjust if needed */
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
}

/* Keep pagination always same position */
.pagination {
    margin-top: 10px;
}
</style>

<?php include("../../includes/footer.php"); ?>
</body>
</html>
