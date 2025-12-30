<?php
include("../config/config.php");

$course = $_GET['course'] ?? '';
$year   = $_GET['year'] ?? '';

$data = [];

if ($course && $year) {
    $stmt = $conn->prepare(
        "SELECT id, student_number, name
         FROM students
         WHERE course = ? AND year_level = ?
         ORDER BY name"
    );
    $stmt->bind_param("si", $course, $year);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
