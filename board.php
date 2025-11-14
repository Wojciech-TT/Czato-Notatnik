<?php
session_start();
include "db.php";
if (!isset($_SESSION['user'])) { header("Location: index.php"); exit; }
$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user['role'] === 'teacher') {
    $content = $_POST['content'];
    $conn->query("DELETE FROM board"); // uproszczenie: tylko jeden wpis
    $stmt = $conn->prepare("INSERT INTO board (content) VALUES (?)");
    $stmt->bind_param("s", $content);
    $stmt->execute();
}
$board = $conn->query("SELECT * FROM board ORDER BY updated_at DESC LIMIT 1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pl">
<head><meta charset="UTF-8"><title>Tablica nauczyciela</title></head>
<body>
  <h2>Tablica nauczyciela</h2>
  <a href="chat.php">Powrót do czatu</a>
  <hr>
  <div style="border:1px solid #000; padding:10px;">
    <?php echo $board ? $board['content'] : "Brak treści"; ?>
  </div>
  <?php if($user['role'] === 'teacher'): ?>
    <form method="POST">
      <textarea name="content" rows="4" cols="50"><?php echo $board['content'] ?? ""; ?></textarea>
      <button type="submit">Zapisz</button>
    </form>
  <?php endif; ?>
</body>
</html>
