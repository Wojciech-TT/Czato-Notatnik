<?php
header("Content-Type: application/json");
include "../db.php";

$messages = $conn->query("
    SELECT m.text, m.created_at, u.name, u.role
    FROM messages m
    JOIN users u ON m.user_id = u.id
    ORDER BY m.created_at DESC
");

$data = [];
while ($row = $messages->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
