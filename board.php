<?php
session_start();
include "db.php";
if (!isset($_SESSION['user'])) { header("Location: index.php"); exit; }
$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user['role'] === 'teacher') {
    $content = $_POST['content'];
    $conn->query("DELETE FROM board"); //  uproszczenie: tylko jeden wpis
    $stmt = $conn->prepare("INSERT INTO board (content) VALUES (?)");
    $stmt->bind_param("s", $content);
    $stmt->execute();
}
$board = $conn->query("SELECT * FROM board ORDER BY updated_at DESC LIMIT 1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Tablica nauczyciela</title>

    <!-- Bootswatch Sketchy -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/sketchy/bootstrap.min.css">
    <link rel="stylesheet" href="css.css">

</head>
<body class="bg-light">

<div class="container mt-5">
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-primary">Tablica nauczyciela</h2>
        <a href="chat.php" class="btn btn-outline-primary">Powrót do czatu</a>
    </div>

    <!-- Tablica treści -->
    <div class="card mb-4">
        <div class="card-header">
            Aktualna treść tablicy
        </div>
        <div class="card-body">
            <p class="card-text">
                <?php echo $board ? nl2br($board['content']) : "Brak treści"; ?>
            </p>
        </div>
    </div>

    <!-- Formularz tylko dla nauczyciela -->
    <?php if ($user['role'] === 'teacher'): ?>
    <div class="card">
        <div class="card-header">
            Edytuj treść tablicy
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group mb-3">
                    <label for="content" class="form-label">Treść</label>
                    <textarea name="content" id="content" class="form-control" rows="4"><?php echo $board['content'] ?? ""; ?></textarea>
                </div>
                <button type="submit" class="btn btn-success w-100">Zapisz</button>
            </form>
        </div>
    </div>
    <?php endif; ?>
</div>

</body>
</html>