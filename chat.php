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
<script>
function loadMessages() {
    fetch("http://192.168.0.10/api/get_messages.php")
        .then(r => r.json())
        .then(data => {
            let html = "";
            data.forEach(msg => {
                html += `<p><b>${msg.name} (${msg.role}):</b> ${msg.text} <i>${msg.created_at}</i></p>`;
            });
            document.getElementById("messages").innerHTML = html;
        });
}

setInterval(loadMessages, 2000);
loadMessages();
</script>

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
    <div id="messages"></div>
</body>
</html>
