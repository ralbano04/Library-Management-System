<?php
$BASE_URL = "/library-management/";
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top px-3">
    <a class="navbar-brand fw-bold" href="<?= $BASE_URL ?>index.php">
        Library System
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">

            <li class="nav-item"><a class="nav-link" href="<?= $BASE_URL ?>pages/dashboard.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $BASE_URL ?>pages/books/books.php">Books</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $BASE_URL ?>pages/students/students.php">Students</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $BASE_URL ?>pages/borrow.php">Borrow</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $BASE_URL ?>pages/return.php">Return</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $BASE_URL ?>pages/pay_penalty.php">Pay</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $BASE_URL ?>pages/transactions.php">Transactions</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $BASE_URL ?>pages/about.php">About</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $BASE_URL ?>pages/faqs.php">FAQs</a></li>


        </ul>
    </div>
</nav>
