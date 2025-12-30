<?php
include("../config/config.php");
include("../includes/header.php");
include("../includes/navbar.php");

if (!isset($_GET['id'])) {
    header("Location: pay_penalty.php");
    exit;
}

$id = (int) $_GET['id'];

/* =========================
   GET CONTINUOUS PENALTY
========================= */
$query = mysqli_query(
    $conn,
    "
    SELECT
        GREATEST(DATEDIFF(CURDATE(), due_date), 0) * 50
        - COALESCE(paid_amount, 0) AS remaining_balance
    FROM transactions
    WHERE id = $id
    "
);

$row = mysqli_fetch_assoc($query);

if (!$row || $row['remaining_balance'] <= 0) {
    header("Location: pay_penalty.php");
    exit;
}

$remaining = $row['remaining_balance'];
?>

<div class="container mt-4">
    <h2>Pay Penalty</h2>

    <form method="POST" action="process_payment.php">
    <input type="hidden" name="transaction_id" value="<?= $id ?>">

    <div class="mb-3">
        <label>Remaining Balance</label>
        <input type="text"
               class="form-control"
               value="₱<?= number_format($remaining, 2) ?>"
               readonly>
    </div>

    <div class="mb-3">
        <label>Amount to Pay</label>
        <input type="number"
               name="amount_paid"
               step="0.01"
               min="1"
               max="<?= $remaining ?>"
               class="form-control"
               required>
    </div>

    <button class="btn btn-success">Submit Payment</button>
    <a href="pay_penalty.php" class="btn btn-secondary">Cancel</a>
</form>

</div>

<?php include("../includes/footer.php"); ?>
</body>
</html>
