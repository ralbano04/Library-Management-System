<?php
include("../config/config.php");

$selected_month = $_GET['month'] ?? "";
$selected_year  = $_GET['year'] ?? "";

$filter_query = "";
if ($selected_month && $selected_year) {
    $filter_query = "WHERE MONTH(t.borrow_date) = '$selected_month' AND YEAR(t.borrow_date) = '$selected_year'";
} elseif ($selected_year) {
    $filter_query = "WHERE YEAR(t.borrow_date) = '$selected_year'";
} elseif ($selected_month) {
    $filter_query = "WHERE MONTH(t.borrow_date) = '$selected_month'";
}

$query = "
    SELECT t.*, 
           s.name AS student_name, 
           b.title AS book_title,
           MONTH(t.borrow_date) AS month,
           YEAR(t.borrow_date) AS year
    FROM transactions t
    JOIN students s ON t.student_id = s.id
    JOIN books b ON t.book_id = b.id
    $filter_query
    ORDER BY year DESC, month DESC, t.borrow_date DESC
";

$result = mysqli_query($conn, $query);

$transactions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $transactions[$row['year']][$row['month']][] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Monthly Transactions Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h2, h3 { text-align: center; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 12px;
        }

        th {
            background: #eee;
        }

        .back-btn {
            display: inline-block;
            margin: 15px;
            padding: 8px 15px;
            background: #007bff;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
        }

        @media print {
            .back-btn { display: none; }
        }
    </style>
</head>
<body>

<a href="transactions.php" class="back-btn">← Back to Transactions</a>

<?php
// Generate dynamic title based on filter
$title = "Library Monthly Transactions Report";

if ($selected_month && $selected_year) {
    $title .= " — " . date("F", mktime(0, 0, 0, $selected_month, 1)) . " " . $selected_year;
} elseif ($selected_month) {
    $title .= " — " . date("F", mktime(0, 0, 0, $selected_month, 1));
} elseif ($selected_year) {
    $title .= " — " . $selected_year;
} else {
    $title .= " (All Records)";
}
?>

<h2><?= $title ?></h2>


<?php
foreach ($transactions as $year => $months):
    foreach ($months as $month => $rows):
        $monthName = date("F", mktime(0,0,0,$month,1));
?>
    <h3><?= "$monthName $year" ?></h3>

    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Book</th>
                <th>Borrow Date</th>
                <th>Due Date</th>
                <th>Return Date</th>
                <th>Penalty</th>   <!-- PENALTY FIRST -->
                <th>Status</th>    <!-- STATUS AFTER -->
            </tr>
        </thead>
        <tbody>

        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= $row['student_name'] ?></td>
                <td><?= $row['book_title'] ?></td>
                <td><?= $row['borrow_date'] ?></td>
                <td><?= $row['due_date'] ?></td>

                <td><?= $row['return_date'] ? $row['return_date'] : "Not Returned" ?></td>

                <!-- PENALTY AMOUNT -->
                <td>
                    ₱<?= number_format($row['penalty'], 2) ?>
                </td>

                <!-- STATUS LOGIC -->
                <td>
                    <?php
                        if ($row['return_date'] == NULL) {
                            echo "Not Returned";
                        } elseif ($row['remaining_balance'] <= 0) {
                            echo "PAID";
                        } else {
                            echo "UNPAID";
                        }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

<?php
    endforeach;
endforeach;
?>

<script>
    window.print();
</script>

</body>
</html>
