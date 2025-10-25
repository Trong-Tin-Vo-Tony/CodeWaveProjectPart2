<?php
require_once "settings.php";  // use the connection info from member 1

$conn = mysqli_connect($host, $user, $pwd, $sql_db);

$sql = "CREATE TABLE IF NOT EXISTS jobs (
  jobRef VARCHAR(10) PRIMARY KEY,
  title VARCHAR(100),
  salary VARCHAR(50),
  employmentType VARCHAR(50),
  reportsTo VARCHAR(100),
  summary TEXT,
  duties TEXT,
  essentialReq TEXT,
  preferableReq TEXT
)";


mysqli_query($conn, $sql);
echo "Jobs table created!";
mysqli_close($conn);
?>
