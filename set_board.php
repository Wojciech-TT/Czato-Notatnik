<?php
header("Content-Type: application/json");
session_start();
include "../db.php";

if (!isset($_SESSION['user'])) {
    echo json_encode(["error" => "not_logged_in"]);
    exit;
}

$user = $_SESSION['user'];

if ($user['role'] !== 'teacher') {
    echo json_encode(["error" => "no_permission"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$content = $data["content"] ?? "";

$conn->query("DELETE FROM board");

$stmt = $conn->prepare("INSERT INTO board (content) VALUES (?)");
$stmt->bind_param("s", $content);
$stmt->execute();

echo json_encode(["status" => "saved"]);
