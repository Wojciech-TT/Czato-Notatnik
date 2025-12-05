<?php
header("Content-Type: application/json");
include "../db.php";

$result = $conn->query("SELECT * FROM board ORDER BY updated_at DESC LIMIT 1");
$board = $result->fetch_assoc();

echo json_encode([
    "content" => $board["content"] ?? "",
    "updated_at" => $board["updated_at"] ?? null
]);
