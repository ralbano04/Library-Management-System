<?php
session_start();
include("../config/config.php");
include("../includes/header.php");
include("../includes/navbar.php");

/* =========================
   CLEAR RECEIPT
========================= */
if (isset($_GET['clear'])) {
    unset($_SESSION['receipt']);
    header("Location: borrow.php");
    exit;
}

/* =========================
   HANDLE BORROW
========================= */
if (isset($_POST['borrow'])) {

    $student_id  = $_POST['student_id'];
    $book_id     = $_POST['book_id'];
    $borrow_date = $_POST['borrow_date'];
    $due_date    = $_POST['due_date'];

    mysqli_query($conn, "
        INSERT INTO transactions (student_id, book_id, borrow_date, due_date)
        VALUES ('$student_id', '$book_id', '$borrow_date', '$due_date')
    ");

    mysqli_query($conn, "
        UPDATE books 
        SET copies_available = copies_available - 1 
        WHERE id = $book_id
    ");

    $_SESSION['receipt'] = [
        'student_id' => $student_id,
        'book_id'    => $book_id,
        'borrow'     => $borrow_date,
        'due'        => $due_date
    ];
    
    header("Location: borrow.php");
    exit;
}
?>

<div class="container mt-4">
    <h2>Borrow a Book</h2>

    <form method="POST">

        <!-- COURSE -->
        <div class="mb-3">
            <label>Course</label>
            <select id="course" class="form-control" required>
                <option value="">Select Course</option>
                <?php
                $courses = mysqli_query($conn, "SELECT DISTINCT course FROM students ORDER BY course");
                while ($c = mysqli_fetch_assoc($courses)):
                ?>
                    <option value="<?= $c['course'] ?>"><?= $c['course'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- YEAR -->
        <div class="mb-3">
            <label>Year Level</label>
            <select id="year" class="form-control" required>
                <option value="">Select Year</option>
                <option value="1">1st Year</option>
                <option value="2">2nd Year</option>
                <option value="3">3rd Year</option>
                <option value="4">4th Year</option>
                <option value="5">5th Year</option>
            </select>
        </div>

        <!-- STUDENT -->
        <div class="mb-3">
            <label>Select Student</label>
            <select name="student_id" id="student" class="form-control" required>
                <option value="">Select Student</option>
            </select>
        </div>

        <!-- BOOK -->
        <div class="mb-3">
            <label>Select Book</label>
            <select name="book_id" class="form-control" required>
                <option value="">Select Book</option>
                <?php
                $books = mysqli_query($conn,
                    "SELECT * FROM books WHERE copies_available > 0 ORDER BY title"
                );
                while ($b = mysqli_fetch_assoc($books)):
                ?>
                    <option value="<?= $b['id'] ?>">
                        <?= $b['title'] ?> (<?= $b['copies_available'] ?> available)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- DATES -->
        <div class="mb-3">
            <label>Borrow Date</label>
            <input type="date" name="borrow_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Due Date</label>
            <input type="date" name="due_date" class="form-control" required>
        </div>

        <button name="borrow" class="btn btn-warning">Confirm Borrow</button>
        <a href="dashboard.php" class="btn btn-secondary">Back</a>
    </form>
</div>

<!-- =========================
     RECEIPT MODAL
========================= -->
<?php if (isset($_SESSION['receipt'])):

$r = $_SESSION['receipt'];

$student = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT student_number, name FROM students WHERE id = {$r['student_id']}"
));

$book = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT title FROM books WHERE id = {$r['book_id']}"
));

$borrowFormatted = date("F d, Y", strtotime($r['borrow']));
$dueFormatted    = date("F d, Y", strtotime($r['due']));
?>

<div class="modal fade" id="receiptModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Borrow Receipt</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="printArea">
                <p><strong>Student:</strong> <?= $student['name'] ?></p>
                <p><strong>Student No:</strong> <?= $student['student_number'] ?></p>
                <p><strong>Book:</strong> <?= $book['title'] ?></p>
                <p><strong>Borrow Date:</strong> <?= $borrowFormatted ?></p>
                <p><strong>Due Date:</strong> <?= $dueFormatted ?></p>
            </div>

            <div class="modal-footer">
                <button class="btn btn-success" onclick="printReceipt()">Print Receipt</button>
                <a href="borrow.php?clear=1" class="btn btn-secondary">Close</a>
            </div>

        </div>
    </div>
</div>

<?php endif; ?>

<!-- =========================
     AUTO-SHOW MODAL
========================= -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const modalEl = document.getElementById("receiptModal");
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
});
</script>

<!-- =========================
     PRINT RECEIPT
========================= -->
<script>
function printReceipt() {
    let content = document.getElementById("printArea").innerHTML;
    let win = window.open('', '', 'width=400,height=600');

    win.document.write(`
        <html>
        <head>
            <title>Borrow Receipt</title>
            <style>
                body { font-family: Arial; padding: 20px; }
            </style>
        </head>
        <body>${content}</body>
        </html>
    `);

    win.document.close();
    win.print();
    win.close();
}
</script>

<!-- =========================
     FETCH STUDENTS
========================= -->
<script>
document.getElementById("course").addEventListener("change", loadStudents);
document.getElementById("year").addEventListener("change", loadStudents);

function loadStudents() {

    let course = document.getElementById("course").value;
    let year   = document.getElementById("year").value;
    let studentSelect = document.getElementById("student");

    studentSelect.innerHTML = '<option value="">Select Student</option>';

    if (!course || !year) return;

    studentSelect.innerHTML = '<option>Loading...</option>';

    fetch(`fetch_students.php?course=${encodeURIComponent(course)}&year=${year}`)
        .then(res => res.json())
        .then(data => {

            studentSelect.innerHTML = '<option value="">Select Student</option>';

            if (data.length === 0) {
                studentSelect.innerHTML += '<option>No students found</option>';
            }

            data.forEach(s => {
                studentSelect.innerHTML += `
                    <option value="${s.id}">
                        ${s.student_number} - ${s.name}
                    </option>`;
            });
        });
}
</script>

<?php include("../includes/footer.php"); ?>
</body>
</html>
