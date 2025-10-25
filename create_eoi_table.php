<?php
require_once "settings.php";
$conn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$sql = "CREATE TABLE IF NOT EXISTS eoi (
  EOInumber INT AUTO_INCREMENT PRIMARY KEY,
  jobRef VARCHAR(10) NOT NULL,
  firstName VARCHAR(50) NOT NULL,
  lastName VARCHAR(50) NOT NULL,
  streetAddress VARCHAR(100),
  suburbTown VARCHAR(50),
  state VARCHAR(20),
  postcode CHAR(4),
  email VARCHAR(100),
  phone VARCHAR(20),
  skills TEXT,
  otherComments TEXT,
  status ENUM('New','Current','Final') DEFAULT 'New'
)";

if (!mysqli_query($conn, $sql)) {
  echo "❌ Error creating table: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
