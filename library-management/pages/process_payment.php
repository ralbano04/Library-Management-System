<?php
include("../config/config.php");

if (!isset($_POST['transaction_id'], $_POST['amount_paid'])) {
    header("Location: /library-management/pages/pay_penalty.php");
    exit;
}

$id     = (int) $_POST['transaction_id'];
$amount = floatval($_POST['amount_paid']);

/* =========================
   GET CONTINUOUS PENALTY
========================= */
$query = mysqli_query(
    $conn,
    "
    SELECT
        GREATEST(DATEDIFF(CURDATE(), due_date), 0) * 50
        - COALESCE(paid_amount, 0) AS remaining_balance,
        COALESCE(paid_amount, 0) AS paid_amount
    FROM transactions
    WHERE id = $id
    "
);

$row = mysqli_fetch_assoc($query);

if (!$row || $row['remaining_balance'] <= 0) {
    header("Location: /library-management/pages/pay_penalty.php");
    exit;
}

/* =========================
   PREVENT OVERPAYMENT
========================= */
if ($amount > $row['remaining_balance']) {
    echo "<script>
        alert('Payment exceeds remaining balance!');
        window.location.href='/library-management/pages/pay-transaction.php?id=$id';
    </script>";
    exit;
}

/* =========================
   UPDATE PAYMENT
========================= */
$new_paid = $row['paid_amount'] + $amount;

mysqli_query(
    $conn,
    "
    UPDATE transactions
    SET paid_amount = $new_paid
    WHERE id = $id
    "
);

/* =========================
   SUCCESS REDIRECT
========================= */
header("Location: /library-management/pages/pay_penalty.php?success=payment");
exit;
