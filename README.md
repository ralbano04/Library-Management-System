📚 Library Management System (University Edition)

A web-based Library Management System designed for university libraries to manage book circulation, student borrowing, penalties, and transaction records efficiently.
This system supports real-world library workflows, including late-return penalties, payment tracking, filtering, reporting, and data export.

🚀 Features
📖 Book Management

Add, update, delete, and search books

Track total and available copies

Categorization and ISBN support

🎓 Student Management

Manage student records (name, student number, course, year level)

Search and filter students

University-ready structure (supports multiple year levels)

🔄 Borrow & Return Transactions

Borrow and return books with due dates

Automatic late-return penalty calculation

Prevent borrowing when copies are unavailable

💰 Penalty & Payment System

Daily penalty calculation for overdue books

Partial payments supported

Remaining balance tracking

Transaction-level penalty records

📊 Transaction History & Reports

View full transaction history

Filter by:

Month

Year range

Pagination support

Export reports to PDF and Excel

🧾 Receipts

Auto-generated borrow and return receipts

Printable format

Session-based receipt handling

🔐 Security & Validation

Input validation and sanitization

Safe SQL query handling

Error handling and debugging support

🛠️ Tech Stack
Layer	Technology
Frontend	HTML, CSS, Bootstrap
Backend	PHP (Procedural)
Database	MySQL
Server	Apache (XAMPP)
PDF Export	DomPDF
Excel Export	PhpSpreadsheet
🗄️ Database Structure

Main tables used:

students

books

transactions

payments (optional depending on implementation)

Key relationships:

One student → many transactions

One book → many transactions

One transaction → multiple payments

⚙️ Installation & Setup
1️⃣ Clone the Repository
git clone https://github.com/your-username/library-management-system.git

2️⃣ Move to XAMPP Directory
C:\xampp\htdocs\library-management-system

3️⃣ Import Database

Open phpMyAdmin

Create a database:

CREATE DATABASE library_db;


Import the provided .sql file into library_db

4️⃣ Configure Database Connection

Edit:

config/config.php

$conn = mysqli_connect("localhost", "root", "", "library_db");

5️⃣ Run the System

Open browser and go to:

http://localhost/library-management-system

📂 Project Structure
library-management-system/
│
├── config/
│   └── config.php
│
├── includes/
│   ├── header.php
│   ├── navbar.php
│   └── footer.php
│
├── pages/
│   ├── books.php
│   ├── students.php
│   ├── borrow.php
│   ├── return.php
│   ├── transactions.php
│   ├── export_pdf.php
│   └── export_excel.php
│
├── assets/
│   ├── css/
│   └── js/
│
├── database/
│   └── library_db.sql
│
└── README.md

📌 System Highlights

Designed for academic / university use

Realistic penalty and payment logic

Clean UI using Bootstrap

Expandable for:

User roles (Admin / Librarian)

Login authentication

Barcode / QR scanning

Analytics dashboard

📈 Future Improvements

Role-based authentication

Email notifications for due dates

Dashboard analytics (charts)

API support

Mobile-friendly optimization

👨‍💻 Developer

Ramon Albano
Bachelor of Science in Computer Science
University Project – Library Management System

📄 License

This project is for educational and academic purposes.
You are free to modify and extend it for learning and research.
