<?php
include("../config/config.php");
include("../includes/header.php");
include("../includes/navbar.php");
?>

<!-- LOAD FONTAWESOME FOR ICONS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" crossorigin="anonymous"></script>

<style>

/* ============================ */
/*       FADE-IN BACKGROUND     */
/* ============================ */

body {
    background: #eef2f3;
    position: relative;
    overflow-x: hidden;
}

/* Background image */
body::before {
    content: "";
    position: fixed;
    top: 180px;
    left: 0;
    width: 100%;
    height: 80vh;
    background-image: url('https://cdn.pixabay.com/photo/2016/11/29/05/08/books-1868073_1280.jpg');
    background-size: cover;
    background-position: center;
    opacity: 0;
    animation: fadeInBackground 2.5s ease-in-out forwards;
    filter: brightness(0.85);
    z-index: -1;
    border-top-left-radius: 30px;
    border-top-right-radius: 30px;
}

@keyframes fadeInBackground {
    from { opacity: 0; }
    to   { opacity: 0.35; }
}

/* ============================ */
/*           TITLES             */
/* ============================ */

.dashboard-subtitle {
    text-align: center;
    font-size: 40px;
    font-weight: 700;
    margin-bottom: 40px;
    color: #555;
}

/* Center dashboard vertically */
.dashboard-wrapper {
    min-height: calc(100vh - 250px);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
}

/* ============================ */
/*        DASHBOARD CARDS       */
/* ============================ */

.menu-grid {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 15px !important;
    transform: translateY(-20px);
}

.menu-card {
    width: 220px;
    height: 220px;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 18px rgba(0,0,0,0.12);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-decoration: none;
    transition: 0.3s ease;
    position: relative;
    color: #333;
}

.menu-card:hover {
    transform: scale(1.07);
}

.menu-icon {
    font-size: 60px;
    margin-bottom: 10px;
}

/* Tooltip */
.tooltip-box {
    display: none;
    position: absolute;
    bottom: -70px;
    background: rgba(0,0,0,0.85);
    color: #fff;
    padding: 10px 15px;
    border-radius: 8px;
    font-size: 14px;
    width: 200px;
    text-align: center;
    z-index: 20;
}

.menu-card:hover .tooltip-box {
    display: block;
}

/* ============================ */
/*         SOCIAL FOOTER        */
/* ============================ */

footer {
    position: fixed;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
    padding-bottom: 15px;
}

.social-footer {
    display: flex;
    justify-content: center;
    gap: 30px;
    margin-bottom: 8px;
}

.social-footer a {
    color: #333;
    font-size: 34px;
    transition: 0.3s ease;
}

.social-footer a:hover {
    color: #4da3ff;
}

.footer-bottom {
    text-align: center;
    font-size: 14px;
    color: #555;
}

/* Mobile scaling */
@media (max-width: 768px) {
    .menu-card {
        width: 170px;
        height: 170px;
    }
    .menu-icon {
        font-size: 45px;
    }
}

</style>

<div class="dashboard-wrapper">

    <p class="dashboard-subtitle">Welcome to the Library Management System</p>

    <div class="menu-grid">

        <a href="books/books.php" class="menu-card">
            <div class="menu-icon">📚</div>
            <h4>Manage Books</h4>
            <div class="tooltip-box">Add, edit, or delete books in the library.</div>
        </a>

        <a href="students/students.php" class="menu-card">
            <div class="menu-icon">🎓</div>
            <h4>Manage Students</h4>
            <div class="tooltip-box">Register and update student information.</div>
        </a>

        <a href="borrow.php" class="menu-card">
            <div class="menu-icon">📘</div>
            <h4>Borrow Books</h4>
            <div class="tooltip-box">Assign books to students for borrowing.</div>
        </a>

        <a href="return.php" class="menu-card">
            <div class="menu-icon">↩️</div>
            <h4>Return Books</h4>
            <div class="tooltip-box">Process returns and apply penalties.</div>
        </a>

        <a href="pay_penalty.php" class="menu-card">
            <div class="menu-icon">💰</div>
            <h4>Pay Penalty</h4>
            <div class="tooltip-box">
                View unpaid penalties and process payments.
            </div>
        </a>

        <a href="transactions.php" class="menu-card">
            <div class="menu-icon">📄</div>
            <h4>Transactions</h4>
            <div class="tooltip-box">View reports, penalties, and history.</div>
        </a>

    </div>
</div>

<!-- ========================= -->
<!--   SOCIAL MEDIA FOOTER     -->
<!-- ========================= -->

<footer>
    <div class="social-footer">
        <a href="https://www.facebook.com/ramalbanooo" target="_blank"><i class="fab fa-facebook"></i></a>
        <a href="https://www.instagram.com/nnah_mon" target="_blank"><i class="fab fa-instagram"></i></a>
        <a href="mailto:ramonalbano@spup.edu.ph"><i class="fas fa-envelope"></i></a>
        <a href="tel:09362132660"><i class="fas fa-phone"></i></a>
    </div>

    <p class="footer-bottom">© 2025 Library Management System. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
