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
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

  <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
    <h3 class="text-center mb-3">Logowanie</h3>
    <form method="POST">
      <div class="mb-3">
        <input type="text" name="name" class="form-control" placeholder="Podaj imię" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Zaloguj</button>
    </form>
    <?php if(isset($error)) echo "<div class='alert alert-danger mt-3'>$error</div>"; ?>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
