<?php
header("Content-Type: application/json");
session_start();
include "../db.php";

if (!isset($_SESSION['user'])) {
    echo json_encode(["error" => "not_logged_in"]);
    exit;
}

$user = $_SESSION['user'];

$data = json_decode(file_get_contents("php://input"), true);
$text = $data["text"] ?? "";

$stmt = $conn->prepare("INSERT INTO messages (user_id, text) VALUES (?, ?)");
$stmt->bind_param("is", $user['id'], $text);
$stmt->execute();

echo json_encode(["status" => "ok"]);
