<?php 
session_start(); 
include "db.php";
if (!isset($_SESSION['user'])) { header("Location: index.php"); exit; }
$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'];
    $stmt = $conn->prepare("INSERT INTO notes (user_id, content) VALUES (?, ?)");
    $stmt->bind_param("is", $user['id'], $content);
    $stmt->execute();
}
$notes = $conn->query("SELECT * FROM notes WHERE user_id=".$user['id']." ORDER BY updated_at DESC");
?>
<!DOCTYPE html>
<html lang="pl">
<head><meta charset="UTF-8"><title>Moje notatki</title></head>
<body>
  <h2>Moje notatki</h2>
  <a href="chat.php">Powrót do czatu</a>
  <hr>
  <form method="POST">
    <textarea name="content" rows="3" cols="50" placeholder="Nowa notatka"></textarea>
    <button type="submit">Dodaj</button>
  </form>
  <h3>Lista notatek:</h3>
  <?php while($row = $notes->fetch_assoc()): ?>
    <p><?php echo $row['content']; ?> <i><?php echo $row['updated_at']; ?></i></p>
  <?php endwhile; ?>
</body>
</html>
