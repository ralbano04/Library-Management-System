<?php
include("../config/config.php");
include("../includes/header.php");
include("../includes/navbar.php");
?>

<style>
/* PREVENT SCROLLING */
body {
    background: #f3f6f9;
    font-family: 'Segoe UI', sans-serif;
    overflow: hidden;
}

/* CENTER CONTENT */
.about-wrapper {
    height: calc(100vh - 120px); /* navbar + footer */
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px;
}

/* MAIN CARD */
.about-container {
    max-width: 900px;
    width: 100%;
    background: #ffffff;
    padding: 75px 30px;
    border-radius: 12px;
    box-shadow: 0 6px 16px rgba(0,0,0,0.12);
}

/* TITLES */
.about-title {
    font-size: 30px;
    font-weight: 700;
    text-align: center;
    margin-bottom: 12px;
    color: #222;
}

.about-section-title {
    font-size: 19px;
    margin: 16px 0 6px;
    font-weight: 600;
    color: #333;
}

/* TEXT */
.about-text {
    font-size: 15px;
    line-height: 1.5;
    color: #444;
}

/* DEVELOPER BOX */
.author-box {
    margin-top: 15px;
    padding: 15px 18px;
    background: #f4f7ff;
    border-left: 5px solid #2563eb;
    border-radius: 10px;
}

.author-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 6px;
    color: #1f3c88;
}

.author-text {
    font-size: 14px;
    line-height: 1.45;
    color: #333;
}
</style>

<div class="about-wrapper">
    <div class="about-container">

        <h1 class="about-title">About This System</h1>

        <p class="about-text">
            The Library Management System streamlines library operations such as book inventory,
            student records, borrowing transactions, returns, and penalty tracking.
            It improves efficiency for both staff and students.
        </p>

        <h2 class="about-section-title">System Features</h2>
        <p class="about-text">
            • Manage student and book records efficiently<br>
            • Borrow and return books with due-date tracking<br>
            • Automatic penalty calculation<br>
            • Export transaction reports to PDF and Excel<br>
            • Clean and user-friendly interface
        </p>

        <h2 class="about-section-title">Purpose of Development</h2>
        <p class="about-text">
            Developed for academic and institutional use to modernize traditional
            library processes and reduce manual workload.
        </p>

        <div class="author-box">
            <div class="author-title">Developer Information</div>
            <p class="author-text">
                Developer:<strong> Ramon Albano</strong><br>
                Email:<strong> ramonalbano@spup.edu.ph</strong><br>
                System Version:<strong> 1.0.0</strong><br>
                Purpose:<strong> Academic Project & Practical Application of PHP and MySQL</strong>
            </p>
        </div>

    </div>
</div>

<?php include("../includes/footer.php"); ?>
</body>
</html>
