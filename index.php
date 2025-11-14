<?php
session_start();
include "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $sql = "SELECT * FROM users WHERE name=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        $_SESSION['user'] = $user;
        header("Location: chat.php");
        exit;
    } else {
        $error = "Nie znaleziono użytkownika!";
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Logowanie</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Logowanie</h2>
  <form method="POST">
    <input type="text" name="name" placeholder="Podaj imię" required>
    <button type="submit">Zaloguj</button>
  </form>
  <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
</body>
</html>
