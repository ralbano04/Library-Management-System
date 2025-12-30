<?php
session_start();
include("../config/config.php");
include("../includes/header.php");
include("../includes/navbar.php");

/* =========================
   CLEAR RECEIPT
========================= */
if (isset($_GET['clear'])) {
    unset($_SESSION['return_receipt']);
    unset($_SESSION['just_returned']);
    header("Location: return.php");
    exit;
}

/* =========================
   HANDLE RETURN
========================= */
if (isset($_POST['return_book'])) {

    $transaction_id = (int) $_POST['transaction_id'];
    $return_date    = $_POST['return_date'];

    // Fetch transaction
    $tx = mysqli_query($conn, "
        SELECT 
            t.*, 
            s.name AS student_name,
            s.student_number,
            b.title AS book_title
        FROM transactions t
        JOIN students s ON t.student_id = s.id
        JOIN books b ON t.book_id = b.id
        WHERE t.id = $transaction_id
    ");
    $data = mysqli_fetch_assoc($tx);

    $borrow_date = $data['borrow_date'];
    $due_date    = $data['due_date'];
    $book_id     = $data['book_id'];

    // Compute late days (STOP at return date)
    $due      = new DateTime($due_date);
    $returned = new DateTime($return_date);
    $late_days = ($returned > $due) ? $due->diff($returned)->days : 0;

    // Penalty finalized here
    $penalty = $late_days * 50;

    // Update transaction
    mysqli_query($conn, "
        UPDATE transactions 
        SET 
            return_date = '$return_date',
            penalty = '$penalty',
            remaining_balance = '$penalty',
            paid_amount = 0
        WHERE id = $transaction_id
    ");

    // Restore book copies
    mysqli_query($conn, "
        UPDATE books 
        SET copies_available = copies_available + 1 
        WHERE id = $book_id
    ");

    // Store receipt (DISPLAY ONLY)
    $_SESSION['return_receipt'] = [
        'transaction_id' => $transaction_id,
        'student'        => $data['student_name'],
        'student_no'     => $data['student_number'],
        'book'           => $data['book_title'],
        'borrow_date'    => $borrow_date,
        'due_date'       => $due_date,
        'return_date'    => $return_date,
        'late_days'      => $late_days,
        'penalty'        => $penalty
    ];

    // Flag to open receipt ONLY ONCE
    $_SESSION['just_returned'] = true;

    header("Location: return.php");
    exit;
}
?>

<div class="container mt-4">
    <h2>Return a Book</h2>

    <?php
    $borrowed = mysqli_query($conn, "
        SELECT 
            t.id, s.name, b.title, t.borrow_date, t.due_date
        FROM transactions t
        JOIN students s ON t.student_id = s.id
        JOIN books b ON t.book_id = b.id
        WHERE t.return_date IS NULL
        ORDER BY t.borrow_date DESC
    ");
    ?>

    <?php if (mysqli_num_rows($borrowed) == 0): ?>
        <div class="alert alert-info">
            No active borrowed books at the moment.
        </div>
    <?php else: ?>

    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label>Select Borrowed Book</label>
            <select name="transaction_id" class="form-control" required>
                <option value="">Select Borrowed Entry</option>
                <?php while ($row = mysqli_fetch_assoc($borrowed)): ?>
                    <option value="<?= $row['id'] ?>">
                        <?= $row['name'] ?> – <?= $row['title'] ?>
                        (Borrowed: <?= $row['borrow_date'] ?> | Due: <?= $row['due_date'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Return Date</label>
            <input type="date" name="return_date" class="form-control" required>
        </div>

        <button name="return_book" class="btn btn-success">Confirm Return</button>
        <a href="dashboard.php" class="btn btn-secondary">Back</a>
    </form>

    <?php endif; ?>
</div>

<!-- =========================
     RETURN RECEIPT MODAL
========================= -->
<?php if (isset($_SESSION['return_receipt'])):
$r = $_SESSION['return_receipt'];

$borrowFormatted = date("F d, Y", strtotime($r['borrow_date']));
$dueFormatted    = date("F d, Y", strtotime($r['due_date']));
$returnFormatted = date("F d, Y", strtotime($r['return_date']));
?>

<div class="modal fade" id="returnReceiptModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Return Receipt</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="printArea">
                <p><strong>Student:</strong> <?= $r['student'] ?></p>
                <p><strong>Student No:</strong> <?= $r['student_no'] ?></p>
                <p><strong>Book:</strong> <?= $r['book'] ?></p>

                <hr>

                <p><strong>Borrow Date:</strong> <?= $borrowFormatted ?></p>
                <p><strong>Due Date:</strong> <?= $dueFormatted ?></p>
                <p><strong>Return Date:</strong> <?= $returnFormatted ?></p>

                <hr>

                <p><strong>Late Days:</strong> <?= $r['late_days'] ?></p>
                <p><strong>Penalty:</strong> ₱<?= number_format($r['penalty'], 2) ?></p>
            </div>

            <div class="modal-footer">
                <button class="btn btn-success" onclick="printReturnReceipt()">Print Receipt</button>
                <a href="return.php?clear=1" class="btn btn-secondary">Close</a>
            </div>

        </div>
    </div>
</div>
<?php endif; ?>

<!-- =========================
     AUTO-OPEN RECEIPT MODAL
========================= -->
<?php if (isset($_SESSION['just_returned'])): ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const modalEl = document.getElementById("returnReceiptModal");
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl, {
            backdrop: 'static',
            keyboard: false
        });
        modal.show();
    }
});
</script>
<?php unset($_SESSION['just_returned']); endif; ?>

<script>
function printReturnReceipt() {
    let content = document.getElementById("printArea").innerHTML;
    let win = window.open('', '', 'width=400,height=600');

    win.document.write(`
        <html>
        <head>
            <title>Return Receipt</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; }
                h3 { text-align: center; }
                p { margin: 6px 0; }
                hr { margin: 10px 0; }
            </style>
        </head>
        <body>
            <h3>Library Return Receipt</h3>
            ${content}
        </body>
        </html>
    `);

    win.document.close();
    win.focus();
    win.print();
    win.close();
}
</script>

<?php include("../includes/footer.php"); ?>
</body>
</html>
