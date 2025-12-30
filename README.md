Library Management System

A web-based Library Management System developed for university use.
This system manages books, students, borrowing and returning transactions, penalties, and reports using a PHP–MySQL stack.

This project was built as an academic system following real library workflows.

Features:
Book Management
Add, edit, delete, and search books
Track total copies and available copies
ISBN and category support
Student Management
Manage student records
Supports multiple year levels and courses
Search and filter students
Borrow and Return Transactions
Borrow books with due dates
Return books with automatic status updates
Prevent borrowing if no copies are available
Penalty and Payment System
Automatic late-return penalty calculation
Supports partial payments
Remaining balance tracking per transaction
Transaction History and Reports
View complete transaction history
Filter by month and year range
Pagination support
Export reports to PDF and Excel
Receipts
Borrow and return receipts
Printable format
Session-based receipt handling

Technology Stack:
Frontend
HTML
CSS
Bootstrap

Backend:
PHP (Procedural)

Database:
MySQL

Server:
Apache (XAMPP)

Libraries:
DomPDF (PDF export)
PhpSpreadsheet (Excel export)

Database Tables:
Main tables used:
students
books
transactions
payments (optional, depending on implementation)

Key relationships:
One student has many transactions
One book has many transactions
One transaction can have multiple payments

Installation and Setup
Clone the repository
git clone https://github.com/your-username/library-management-system.git

Move the project to XAMPP htdocs
C:\xampp\htdocs\library-management-system

Import the database
Open phpMyAdmin

Create a database named:
CREATE DATABASE library_db;

Import the provided library_db.sql file into library_db
Configure database connection

Edit the file:
config/config.php

Update the connection:
$conn = mysqli_connect("localhost", "root", "", "library_db");

Run the system

Open your browser and go to:
http://localhost/library-management-system

Project Structure

library-management-system
├── config
│ └── config.php
├── includes
│ ├── header.php
│ ├── navbar.php
│ └── footer.php
├── pages
│ ├── books.php
│ ├── students.php
│ ├── borrow.php
│ ├── return.php
│ ├── transactions.php
│ ├── export_pdf.php
│ └── export_excel.php
├── assets
│ ├── css
│ └── js
├── database
│ └── library_db.sql
└── README.md

System Highlights
Designed for academic and university use
Realistic penalty and payment logic
Clean and simple user interface using Bootstrap
Easily extendable for future features
Possible Future Improvements
Role-based authentication (Admin / Librarian)
Login system
Email notifications for due dates
Barcode or QR code scanning
Analytics dashboard

Developer:
Ramon Albano
Bachelor of Science in Computer Science

License:
This project is intended for educational and academic purposes.
You are free to modify and extend it for learning and research.
