<?php
session_start();
include "db.php";
if (!isset($_SESSION['user'])) { header("Location: index.php"); exit; }

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = $_POST['text'];
    $stmt = $conn->prepare("INSERT INTO messages (user_id, text) VALUES (?, ?)");
    $stmt->bind_param("is", $user['id'], $text);
    $stmt->execute();
}
$messages = $conn->query("SELECT m.text, m.created_at, u.name, u.role 
                          FROM messages m JOIN users u ON m.user_id=u.id 
                          ORDER BY m.created_at DESC");
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Czat</title>
</head>
<body>
  <h2>Witaj, <?php echo $user['name']; ?> (<?php echo $user['role']; ?>)</h2>
  <a href="board.php">Tablica nauczyciela</a> | 
  <a href="notes.php">Moje notatki</a> | 
  <a href="logout.php">Wyloguj</a>
  <hr>
  <form method="POST">
    <input type="text" name="text" placeholder="Twoja wiadomość" required>
    <button type="submit">Wyślij</button>
  </form>
  <h3>Wiadomości:</h3>
  <?php while($row = $messages->fetch_assoc()): ?>
    <p><b><?php echo $row['name']; ?> (<?php echo $row['role']; ?>):</b> 
       <?php echo $row['text']; ?> 
       <i><?php echo $row['created_at']; ?></i></p>
  <?php endwhile; ?>
</body>
</html>
