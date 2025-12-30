<?php 
include("../../config/config.php"); 
include("../../includes/header.php"); 
include("../../includes/navbar.php"); 

// SEARCH & FILTER
$search   = $_GET['search'] ?? "";
$category = $_GET['category'] ?? "";

// SORTING (A–Z / Z–A)
$sort  = $_GET['sort'] ?? "id";
$order = $_GET['order'] ?? "ASC";

if (!in_array($sort, ["id", "title"])) {
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
    $where[] = "(title LIKE '%$search%' OR author LIKE '%$search%')";
}
if ($category !== "") {
    $where[] = "category = '$category'";
}
$whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

// COUNT
$count_query = "SELECT COUNT(*) AS total FROM books $whereSQL";
$count_result = mysqli_query($conn, $count_query);
$total_rows = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_rows / $limit);

// MAIN QUERY
$query = "
    SELECT * FROM books
    $whereSQL
    ORDER BY $sort $order
    LIMIT $limit OFFSET $offset
";
$result = mysqli_query($conn, $query);
?>

<div class="container mt-5">

    <h2 class="mb-3">Books</h2>

    <!-- ACTION BUTTONS -->
    <div class="d-flex gap-2 mb-3">
        <a href="add_book.php" class="btn btn-primary">Add New Book</a>
        <a href="../dashboard.php" class="btn btn-secondary">Back</a>
    </div>

    <!-- SEARCH & FILTER -->
    <form method="GET" class="row g-3 mb-3 align-items-end">

        <div class="col-md-4">
            <input type="text" name="search" value="<?= $search ?>" class="form-control"
                   placeholder="Search by title or author">
        </div>

        <div class="col-md-3">
            <select name="category" class="form-control">
                <option value="">All Categories</option>
                <?php
                $cats = mysqli_query($conn, "SELECT DISTINCT category FROM books");
                while ($c = mysqli_fetch_assoc($cats)):
                ?>
                    <option value="<?= $c['category'] ?>" <?= ($category == $c['category']) ? "selected" : "" ?>>
                        <?= $c['category'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- A–Z / Z–A BUTTONS -->
        <div class="col-md-2 d-flex gap-2">
            <a href="?sort=title&order=ASC" class="btn btn-outline-primary w-50">A–Z</a>
            <a href="?sort=title&order=DESC" class="btn btn-outline-primary w-50">Z–A</a>
        </div>

        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-dark w-50">Apply</button>
            <a href="books.php" class="btn btn-secondary w-50">Clear</a>
        </div>

    </form>

    <!-- BOOKS TABLE -->
    <div class="table-wrapper">
    <table class="table table-bordered table-striped books-table">
        <thead class="table-dark">
            <tr>
                <th>No.</th>
                <th>
                    <a href="?sort=title&order=<?= ($order === "ASC" ? "DESC" : "ASC") ?>"
                       class="sort-title-link">
                        Title
                    </a>
                </th>
                <th>Author</th>
                <th>Category</th>
                <th>ISBN</th>
                <th>Copies</th>
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
                <td title="<?= htmlspecialchars($row['title']) ?>">
                    <?= $row['title']; ?>
                </td>
                <td><?= $row['author']; ?></td>
                <td><?= $row['category']; ?></td>
                <td><?= $row['isbn']; ?></td>
                <td><?= $row['copies_available']."/".$row['copies_total']; ?></td>
                <td>
                    <div class="action-buttons">
                        <a href="edit_book.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_book.php?id=<?= $row['id']; ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this book?');">
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
                <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
            </li>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>

            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
            </li>

        </ul>
    </nav>
    </div>
</div>

<!-- STYLES -->
<style>
/* FIX COLUMN WIDTHS */
.books-table {
    table-layout: fixed;
    width: 100%;
}

.books-table th:nth-child(1),
.books-table td:nth-child(1) { width: 60px; }

.books-table th:nth-child(2),
.books-table td:nth-child(2) { width: 320px; }

.books-table th:nth-child(3),
.books-table td:nth-child(3) { width: 200px; }

.books-table th:nth-child(4),
.books-table td:nth-child(4) { width: 150px; }

.books-table th:nth-child(5),
.books-table td:nth-child(5) { width: 130px; }

.books-table th:nth-child(6),
.books-table td:nth-child(6) { width: 90px; }

.books-table th:nth-child(7),
.books-table td:nth-child(7) { width: 160px; }

/* TEXT CONTROL */
.books-table td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* ACTION BUTTON ALIGNMENT */
.action-buttons {
    display: flex;
    gap: 6px;
}

/* SORT TITLE */
.sort-title-link {
    color: #fff;
    text-decoration: underline;
    font-weight: 600;
}

.sort-title-link:hover {
    color: #ffff01;
}

/* PAGINATION */
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

<?php include("../../includes/footer.php"); ?>
</body>
</html>
