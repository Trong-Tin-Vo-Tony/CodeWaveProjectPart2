<?php
require_once "settings.php";
$conn = mysqli_connect($host, $user, $pwd, $sql_db);

if ($conn) {
  echo "✅ Connected successfully to the database!";
} else {
  echo "❌ Connection failed: " . mysqli_connect_error();
}
?>
