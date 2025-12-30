<?php
include("../config/config.php");

// GET FILTERS
$month     = $_GET['month'] ?? "";
$from_year = $_GET['from_year'] ?? "";
$to_year   = $_GET['to_year'] ?? "";

/* =======================
   BUILD FILE NAME
======================= */
$filename_parts = ["Transactions"];

if ($month !== "") {
    $filename_parts[] = date("F", mktime(0, 0, 0, $month, 1));
}

if ($from_year !== "" && $to_year !== "") {
    $filename_parts[] = $from_year . "-" . $to_year;
} elseif ($from_year !== "") {
    $filename_parts[] = "From-" . $from_year;
} elseif ($to_year !== "") {
    $filename_parts[] = "UpTo-" . $to_year;
}

$filename = implode("_", $filename_parts) . ".csv";

/* =======================
   BUILD FILTER QUERY
======================= */
$conditions = [];

if ($month !== "") {
    $conditions[] = "MONTH(t.borrow_date) = '$month'";
}

if ($from_year !== "" && $to_year !== "") {
    $conditions[] = "YEAR(t.borrow_date) BETWEEN '$from_year' AND '$to_year'";
} elseif ($from_year !== "") {
    $conditions[] = "YEAR(t.borrow_date) >= '$from_year'";
} elseif ($to_year !== "") {
    $conditions[] = "YEAR(t.borrow_date) <= '$to_year'";
}

$where = "";
if (!empty($conditions)) {
    $where = "WHERE " . implode(" AND ", $conditions);
}

/* =======================
   CSV HEADERS
======================= */
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=\"$filename\"");

$output = fopen("php://output", "w");

/* =======================
   CSV COLUMN HEADERS
======================= */
fputcsv($output, [
    "Year",
    "Month",
    "Student",
    "Book",
    "Borrow Date",
    "Due Date",
    "Return Date",
    "Penalty",
    "Status"
]);

/* =======================
   QUERY
======================= */
$query = "
    SELECT t.*, 
           s.name AS student_name,
           b.title AS book_title,
           YEAR(t.borrow_date) AS yr,
           MONTH(t.borrow_date) AS mo
    FROM transactions t
    JOIN students s ON t.student_id = s.id
    JOIN books b ON t.book_id = b.id
    $where
    ORDER BY yr DESC, mo DESC, t.borrow_date DESC
";

$result = mysqli_query($conn, $query);

/* =======================
   OUTPUT ROWS
======================= */
while ($row = mysqli_fetch_assoc($result)) {

    if ($row['return_date'] === NULL) {
        $status = "NOT RETURNED";
    } elseif ($row['remaining_balance'] <= 0) {
        $status = "PAID";
    } else {
        $status = "UNPAID (₱" . number_format($row['remaining_balance'], 2) . ")";
    }

    fputcsv($output, [
        $row['yr'],
        date("F", mktime(0, 0, 0, $row['mo'], 1)),
        $row['student_name'],
        $row['book_title'],
        $row['borrow_date'],
        $row['due_date'],
        $row['return_date'] ?: "Not Returned",
        number_format($row['penalty'], 2),
        $status
    ]);
}

fclose($output);
exit;
