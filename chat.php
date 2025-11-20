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

    <!-- Bootswatch Sketchy -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/sketchy/bootstrap.min.css">

    <!-- Twój osobny plik CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="chat-container">

    <h2 class="text-center mb-3">
        Witaj, <strong><?php echo $user['name']; ?></strong>  
        <span class="badge text-bg-info"><?php echo $user['role']; ?></span>
    </h2>

    <nav class="mb-3 text-center">
        <a href="board.php" class="btn btn-outline-primary btn-sm">Tablica nauczyciela</a>
        <a href="notes.php" class="btn btn-outline-secondary btn-sm">Moje notatki</a>
        <a href="logout.php" class="btn btn-danger btn-sm">Wyloguj</a>
    </nav>

    <form method="POST" class="mb-4">
        <div class="input-group">
            <input type="text" name="text" class="form-control" placeholder="Twoja wiadomość..." required>
            <button class="btn btn-success">Wyślij</button>
        </div>
    </form>

    <h4>Wiadomości:</h4>

    <div class="message-box">
        <?php while($row = $messages->fetch_assoc()): ?>
            <div class="message">
                <div class="msg-header">
                    <?php echo $row['name']; ?> 
                    <span class="badge bg-warning"><?php echo $row['role']; ?></span>
                </div>
                <div><?php echo $row['text']; ?></div>
                <div class="msg-time">
                    <?php echo $row['created_at']; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

</div>

</body>
</html>