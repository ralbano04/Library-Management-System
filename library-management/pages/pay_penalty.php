<?php
include("../config/config.php");
include("../includes/header.php");
include("../includes/navbar.php");

/* =========================
   SEARCH
========================= */
$search = $_GET['search'] ?? "";

/* =========================
   PAGINATION
========================= */
$limit  = 10;
$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page   = max($page, 1);
$offset = ($page - 1) * $limit;

/* =========================
   COUNT (USE STORED BALANCE)
========================= */
$safe = mysqli_real_escape_string($conn, $search);

$countQuery = "
    SELECT COUNT(*) AS total
    FROM transactions t
    JOIN students s ON t.student_id = s.id
    JOIN books b ON t.book_id = b.id
    WHERE t.return_date IS NOT NULL
      AND t.remaining_balance > 0
";

if ($search !== "") {
    $countQuery .= "
        AND (s.name LIKE '%$safe%' OR b.title LIKE '%$safe%')
    ";
}

$totalRows  = mysqli_fetch_assoc(mysqli_query($conn, $countQuery))['total'];
$totalPages = ceil($totalRows / $limit);

/* =========================
   MAIN QUERY (USE STORED BALANCE)
========================= */
$query = "
    SELECT 
        t.id,
        t.borrow_date,
        t.due_date,
        t.return_date,
        s.name AS student_name,
        b.title AS book_title,
        t.remaining_balance
    FROM transactions t
    JOIN students s ON t.student_id = s.id
    JOIN books b ON t.book_id = b.id
    WHERE t.return_date IS NOT NULL
      AND t.remaining_balance > 0
";

if ($search !== "") {
    $query .= "
        AND (s.name LIKE '%$safe%' OR b.title LIKE '%$safe%')
    ";
}

$query .= "
    ORDER BY t.return_date DESC
    LIMIT $limit OFFSET $offset
";

$result = mysqli_query($conn, $query);
?>

<?php if (isset($_GET['success']) && $_GET['success'] === 'payment'): ?>
<script>
window.onload = function () {
    alert("Payment recorded successfully!");
    window.history.replaceState({}, document.title, window.location.pathname);
};
</script>
<?php endif; ?>

<div class="container mt-4">

    <h2 class="mb-3">Pay Penalty</h2>

    <!-- SEARCH BAR -->
    <form method="GET" id="filterForm" class="mb-3 d-flex gap-2">
        <input type="text"
               name="search"
               id="searchInput"
               value="<?= htmlspecialchars($search) ?>"
               class="form-control w-25"
               placeholder="Search student or book">

        <button type="submit" class="btn btn-primary px-4">Apply</button>

        <button type="button"
                class="btn btn-secondary px-4"
                onclick="clearFilter()">Clear</button>

        <a href="dashboard.php" class="btn btn-dark px-4">Back</a>
    </form>

    <!-- TABLE -->
    <div class="table-container">
        <table class="table table-bordered table-striped fixed-table">
            <thead class="table-dark text-center">
                <tr>
                    <th>Student</th>
                    <th>Book</th>
                    <th>Borrow Date</th>
                    <th>Due Date</th>
                    <th>Return Date</th>
                    <th>Remaining Balance</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
            <?php if (mysqli_num_rows($result) == 0): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        No unpaid penalties found.
                    </td>
                </tr>
            <?php else: ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['student_name']) ?></td>
                        <td><?= htmlspecialchars($row['book_title']) ?></td>
                        <td class="text-center"><?= date("F d, Y", strtotime($row['borrow_date'])) ?></td>
                        <td class="text-center"><?= date("F d, Y", strtotime($row['due_date'])) ?></td>
                        <td class="text-center"><?= date("F d, Y", strtotime($row['return_date'])) ?></td>
                        <td class="text-danger fw-bold text-center">
                            ₱<?= number_format($row['remaining_balance'], 2) ?>
                        </td>
                        <td class="text-center">
                            <a href="pay-transaction.php?id=<?= $row['id'] ?>"
                               class="btn btn-warning btn-sm w-100">
                                Pay
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- =========================
   PAGINATION
========================= -->
<?php if ($totalPages > 1): ?>
<div class="pagination-fixed-viewport">
    <ul class="pagination pagination-custom">

        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
            <a class="page-link"
               href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">
                Previous
            </a>
        </li>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                <a class="page-link"
                   href="?page=<?= $i ?>&search=<?= urlencode($search) ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
            <a class="page-link"
               href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">
                Next
            </a>
        </li>

    </ul>
</div>
<?php endif; ?>

<style>
.fixed-table {
    table-layout: fixed;
}
.fixed-table th,
.fixed-table td {
    vertical-align: middle;
}

.pagination-fixed-viewport {
    position: fixed;
    left: 0;
    right: 0;
    bottom: calc(70px + 80px);
    display: flex;
    justify-content: center;
    z-index: 1050;
}

.pagination-custom .page-link {
    min-width: 40px;
    height: 38px;
    background-color: #edf0f3ff;
    color: #000;
    border: 1px solid #dee2e6;
    margin: 0 2px;
    border-radius: 6px;
}

.pagination-custom .page-item.active .page-link {
    background-color: #0d6efd;
    color: #fff;
    border-color: #0d6efd;
}
</style>

<script>
function clearFilter() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterForm').submit();
}
</script>

<?php include("../includes/footer.php"); ?>
</body>
</html>
