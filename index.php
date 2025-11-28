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
<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Logowanie</title>
  <link rel="stylesheet" href="login.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/sketchy/bootstrap.min.css">
</head>
<body>
  <main class="container mt-5" style="max-width: 500px;">
    <h1 class="text-center mb-4">Panel logowania</h1>

    <form method="POST">

      <div class="mb-3">
        <label for="login" class="form-label fw-semibold">Login:</label>
        <input type="text" id="login" name="login" class="form-control" required>
      </div>

     
      <button type="submit" class="btn btn-info w-100">Zaloguj</button>

      <?php 
      if (isset($error)) {
          echo "<p class='mt-3 text-danger fw-bold text-center'>$error</p>";
      }
      ?>

      
    </form>
  </main>
</body>
</html>