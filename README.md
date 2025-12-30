# Library Management System

A web-based Library Management System developed for university use.

This system manages books, students, borrowing and returning transactions,
penalties, and reports using a PHP–MySQL stack.

This project was built as an academic system following real library workflows.

## Features

### Book Management
- Add, edit, delete, and search books
- Track total copies and available copies
- ISBN and category support

### Student Management
- Manage student records
- Supports multiple year levels and courses
- Search and filter students

### Borrow and Return
- Borrow books with due dates
- Return books with automatic updates
- Prevent borrowing when copies are unavailable

### Penalty and Payments
- Automatic late penalty calculation
- Partial payment support
- Remaining balance tracking

### Reports
- Transaction history
- Filter by month and year
- Export to PDF and Excel

## Technology Stack

Frontend
- HTML
- CSS
- Bootstrap

Backend
- PHP (Procedural)

Database
- MySQL

Server
- Apache (XAMPP)

Libraries
- DomPDF
- PhpSpreadsheet

## Installation

1. Clone the repository
git clone https://github.com/your-username/library-management-system.git

2. Move to XAMPP htdocs
C:\xampp\htdocs\library-management-system

3. Create database
CREATE DATABASE library_db;

4. Import library_db.sql

5. Configure config/config.php
$conn = mysqli_connect("localhost", "root", "", "library_db");

6. Run in browser
http://localhost/library-management-system

## Database Setup
This system uses an empty database by default.
Books and students are added through the system interface.

Steps:
1. Create a database named `library_db`
2. Import `database/library_db.sql` using phpMyAdmin
3. Configure the database connection in `config/config.php`

## Developer

Ramon Albano  
Bachelor of Science in Computer Science

## License

Educational and academic use only.
