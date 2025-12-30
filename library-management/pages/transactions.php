<?php
include("../config/config.php");
include("../includes/header.php");
include("../includes/navbar.php");

/* =======================
   GET FILTERS
======================= */
$selected_month = $_GET['month'] ?? "";
$from_year      = $_GET['from_year'] ?? "";
$to_year        = $_GET['to_year'] ?? "";
$page           = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

$limit  = 10;
$page   = max($page, 1);
$offset = ($page - 1) * $limit;

/* =======================
   BUILD FILTER QUERY
======================= */
$where = "WHERE 1=1";

if ($selected_month !== "") {
    $where .= " AND MONTH(t.borrow_date) = '$selected_month'";
}

if ($from_year && $to_year) {
    $where .= " AND YEAR(t.borrow_date) BETWEEN '$from_year' AND '$to_year'";
} elseif ($from_year) {
    $where .= " AND YEAR(t.borrow_date) >= '$from_year'";
} elseif ($to_year) {
    $where .= " AND YEAR(t.borrow_date) <= '$to_year'";
}

/* =======================
   GET YEARS FOR DROPDOWN
======================= */
$year_result = mysqli_query(
    $conn,
    "SELECT DISTINCT YEAR(borrow_date) AS yr 
     FROM transactions 
     ORDER BY yr DESC"
);

$years = [];
while ($y = mysqli_fetch_assoc($year_result)) {
    $years[] = $y['yr'];
}

/* =======================
   COUNT TOTAL ROWS
======================= */
$count_query = "
    SELECT COUNT(*) AS total
    FROM transactions t
    $where
";
$count_result = mysqli_query($conn, $count_query);
$total_rows   = mysqli_fetch_assoc($count_result)['total'];
$total_pages  = ceil($total_rows / $limit);

/* =======================
   MAIN QUERY (LATEST → OLDEST)
======================= */
$query = "
    SELECT 
        t.*,
        s.name AS student_name,
        b.title AS book_title,
        MONTHNAME(t.borrow_date) AS month_name,
        YEAR(t.borrow_date) AS year_num
    FROM transactions t
    JOIN students s ON t.student_id = s.id
    JOIN books b ON t.book_id = b.id
    $where
    ORDER BY t.borrow_date DESC, t.id DESC
    LIMIT $limit OFFSET $offset
";

$result = mysqli_query($conn, $query);
?>

<div class="container mt-4">
    <h2>Transaction History</h2>

    <!-- ACTION BUTTONS -->
    <div class="mb-3 d-flex gap-2">
        <a href="export_pdf.php?month=<?= $selected_month ?>&from_year=<?= $from_year ?>&to_year=<?= $to_year ?>" class="btn btn-danger">Export PDF</a>
        <a href="export_excel.php?month=<?= $selected_month ?>&from_year=<?= $from_year ?>&to_year=<?= $to_year ?>" class="btn btn-success">Export Excel</a>
        <a href="dashboard.php" class="btn btn-secondary">Back</a>
    </div>

    <!-- FILTER FORM -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label>Month</label>
            <select name="month" class="form-control">
                <option value="">All Months</option>
                <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= $m ?>" <?= ($selected_month == $m ? "selected" : "") ?>>
                        <?= date("F", mktime(0,0,0,$m,1)) ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <div class="col-md-3">
            <label>From Year</label>
            <select name="from_year" class="form-control">
                <option value="">Any</option>
                <?php foreach ($years as $yr): ?>
                    <option value="<?= $yr ?>" <?= ($from_year == $yr ? "selected" : "") ?>>
                        <?= $yr ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-3">
            <label>To Year</label>
            <select name="to_year" class="form-control">
                <option value="">Any</option>
                <?php foreach ($years as $yr): ?>
                    <option value="<?= $yr ?>" <?= ($to_year == $yr ? "selected" : "") ?>>
                        <?= $yr ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-3 d-flex align-items-end gap-2">
            <button class="btn btn-primary w-50">Apply</button>
            <a href="transactions.php" class="btn btn-secondary w-50">Clear</a>
        </div>
    </form>

    <!-- TRANSACTION TABLE -->
     <div class="table-wrapper">
    <table class="table table-bordered table-striped transaction-table">
        <thead class="table-dark">
            <tr>
                <th>Month</th>
                <th>Year</th>
                <th>Student</th>
                <th>Book</th>
                <th>Borrow Date</th>
                <th>Due Date</th>
                <th>Return Date</th>
                <th>Penalty</th>
            </tr>
        </thead>
        <tbody>

        <?php if (mysqli_num_rows($result) == 0): ?>
            <tr>
                <td colspan="8" class="text-center text-muted">No transactions found.</td>
            </tr>
        <?php endif; ?>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['month_name'] ?></td>
                <td><?= $row['year_num'] ?></td>
                <td><?= $row['student_name'] ?></td>
                <td><?= $row['book_title'] ?></td>
                <td><?= $row['borrow_date'] ?></td>
                <td><?= $row['due_date'] ?></td>
                <td><?= $row['return_date'] ?: "Not Returned" ?></td>
                <td>
                    <?php if ($row['return_date'] === NULL): ?>
                        <span class="text-warning fw-bold">Not Returned</span>
                    <?php elseif ($row['remaining_balance'] <= 0): ?>
                        <span class="text-success fw-bold">PAID</span>
                    <?php else: ?>
                        <span class="text-danger fw-bold">
                            ₱<?= number_format($row['remaining_balance'], 2) ?>
                        </span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>

        </tbody>
    </table>
    </div>    

    <!-- PAGINATION -->
    <?php if ($total_pages > 1): ?>
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <li class="page-item <?= ($page <= 1 ? 'disabled' : '') ?>">
                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page'=>$page-1])) ?>">Previous</a>
            </li>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($page == $i ? 'active' : '') ?>">
                    <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page'=>$i])) ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>

            <li class="page-item <?= ($page >= $total_pages ? 'disabled' : '') ?>">
                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page'=>$page+1])) ?>">Next</a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<!-- STYLES -->
<style>
.transaction-table {
    table-layout: fixed;
    width: 100%;
}
/* ===============================
   FIX ROW HEIGHT (ADD LENGTH)
=============================== */
.transaction-table th,
.transaction-table td {
    height: 5px;              /* increase/decrease as needed */
    line-height: 1.3;
    vertical-align: middle;    /* center text vertically */
    padding-top: 10px;
    padding-bottom: 10px;
}
.transaction-table th:nth-child(1),
.transaction-table td:nth-child(1) { width: 120px; }
.transaction-table th:nth-child(2),
.transaction-table td:nth-child(2) { width: 90px; }
.transaction-table th:nth-child(3),
.transaction-table td:nth-child(3) { width: 200px; }
.transaction-table th:nth-child(4),
.transaction-table td:nth-child(4) { width: 260px; }
.transaction-table th:nth-child(5),
.transaction-table td:nth-child(5),
.transaction-table th:nth-child(6),
.transaction-table td:nth-child(6),
.transaction-table th:nth-child(7),
.transaction-table td:nth-child(7) { width: 140px; }
.transaction-table th:nth-child(8),
.transaction-table td:nth-child(8) { width: 140px; }

.pagination .page-link {
    background-color: #f1f3f5;
    color: #000;
    border-radius: 6px;
}

.pagination .page-item.active .page-link {
    background-color: #0d6efd;
    color: #fff;
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
    margin-top: 20px;
}

</style>

<?php include("../includes/footer.php"); ?>
</body>
</html>
