<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'settings.php';

$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $u = trim($_POST['username'] ?? '');
  $p = $_POST['password'] ?? '';

  $conn = @mysqli_connect($host2, $user, $pwd, $sql_db, $port2);
  if (!$conn) {
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
    if (!$conn) {
      $err = 'Database connection failed.';
    }
  } 
  else {
    $stmt = $conn->prepare("SELECT user_id, username, password_hash, role FROM user WHERE username = ?");
    $stmt->bind_param('s', $u);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
      if (password_verify($p, $row['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id']  = (int)$row['user_id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role']     = $row['role'];
        $_SESSION['csrf']     = bin2hex(random_bytes(32));
        header('Location: manage.php');
        exit;
      }
    }
    $err = 'Invalid username or password.';
  }
}

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Manager Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="styles/styles.css">
  <!-- <link rel="stylesheet" href="styles/admin.css"> -->
</head>
<body class='page-login'>

<?php include 'header.inc'; ?>

<main class="container">
  <div class="card login-panel" style="max-width:520px;margin:1.5rem auto;">
    <h1>HR Manager Login</h1>
    <p class="muted">Use your credentials to access the manager dashboard.</p>

    <?php if ($err): ?><div class="error"><?= h($err) ?></div><?php endif; ?>

    <form method="post" action="">
      <label>Username</label>
      <input type="text" name="username" required autocomplete="username">

      <label>Password</label>
      <input type="password" name="password" required autocomplete="current-password">

      <div style="display:flex;gap:.6rem;margin-top:.8rem;">
        <button type="submit">Sign in</button>
        <a class="btn secondary" href="home.php" style="padding:.65rem .9rem;border-radius:10px;border:1px solid var(--border);text-decoration:none">Back</a>
      </div>
    </form>
  </div>
</main>

<?php if (file_exists('footer.inc')) include 'footer.inc'; ?>
</body>
</html>