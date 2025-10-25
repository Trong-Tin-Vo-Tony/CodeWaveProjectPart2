<?php
session_start();
require_once 'settings.php';

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST["username"]);
  $password = $_POST["password"];

  $conn = @mysqli_connect($host2, $user, $pwd, $sql_db, $port2);
  if (!$conn) {
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db, $port);
    if (!$conn) {
      die("Connection failed.");
    }
  }

  $stmt = $conn->prepare("SELECT user_id, username, password_hash FROM user WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row["password_hash"])) {
      $_SESSION["user_id"] = $row["user_id"];
      $_SESSION["username"] = $row["username"];
      header("Location: manage.php");
      exit();
    }
  }
  $error = "Invalid username or password.";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manager Login</title>
</head>
<body>
  <h2>Manager Login</h2>
  <form method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>
    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>
  <p style="color:red;"><?php echo $error; ?></p>
</body>
</html>
