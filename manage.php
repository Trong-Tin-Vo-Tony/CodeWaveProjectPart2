<?php
require_once 'auth.php';
require_once 'settings.php';
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) die("Database connection failed.");

function h($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

// Handle updates or deletions
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Update status
  if (isset($_POST["update_status"])) {
    $id = intval($_POST["job_ref_num"]);
    $status = $_POST["status"];
    $stmt = $conn->prepare("UPDATE eoi SET status=? WHERE job_ref_num=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $message = "Status updated for job_ref_num $id.";
  }
  // Delete by job_ref
  if (isset($_POST["delete_jobref"])) {
    $jobref = intval($_POST["job_ref_num"]);
    $stmt = $conn->prepare("DELETE FROM eoi WHERE job_ref_num=?");
    $stmt->bind_param("i", $jobref);
    $stmt->execute();
    $message = "All EOIs with Job Ref $jobref deleted.";
  }
}

// Filtering
$where = "";
if (!empty($_GET["first_name"])) {
  $fname = "%" . $_GET["first_name"] . "%";
  $where = "WHERE first_name LIKE ?";
}
elseif (!empty($_GET["last_name"])) {
  $lname = "%" . $_GET["last_name"] . "%";
  $where = "WHERE last_name LIKE ?";
}
elseif (!empty($_GET["job_ref_num"])) {
  $jobref = intval($_GET["job_ref_num"]);
  $where = "WHERE job_ref_num = ?";
}

// Sorting
$sort = $_GET["sort"] ?? "job_ref_num";
$order = ($_GET["order"] ?? "ASC") === "DESC" ? "DESC" : "ASC";

// Query builder
$sql = "SELECT job_ref_num, first_name, last_name, dob, gender, st_address, suburb_town, state, postcode, email, phone, status FROM eoi";
$sql .= " $where ORDER BY $sort $order";

$stmt = $conn->prepare($sql);

if (isset($fname)) $stmt->bind_param("s", $fname);
elseif (isset($lname)) $stmt->bind_param("s", $lname);
elseif (isset($jobref)) $stmt->bind_param("i", $jobref);

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage EOIs</title>
</head>
<body>
<h1>Manage EOIs</h1>
<p style="color:green;"><?php echo $message; ?></p>

<!-- Search form -->
<form method="get">
  <input type="text" name="job_ref_num" placeholder="Job Ref">
  <input type="text" name="first_name" placeholder="First Name">
  <input type="text" name="last_name" placeholder="Last Name">
  <select name="sort">
    <option value="job_ref_num">Job Ref</option>
    <option value="first_name">First Name</option>
    <option value="last_name">Last Name</option>
  </select>
  <select name="order">
    <option value="ASC">ASC</option>
    <option value="DESC">DESC</option>
  </select>
  <button type="submit">Search / Sort</button>
</form>

<!-- Delete by job_ref -->
<form method="post" style="margin-top:15px;">
  <input type="number" name="job_ref_num" placeholder="Enter Job Ref to Delete" required>
  <button type="submit" name="delete_jobref">Delete by Job Ref</button>
</form>

<!-- Table of EOIs -->
<table border="1" cellpadding="6" cellspacing="0" style="margin-top:20px;">
  <tr>
    <th>Job Ref</th><th>First</th><th>Last</th><th>Email</th><th>Phone</th><th>Status</th><th>Update</th>
  </tr>
  <?php while ($row = $result->fetch_assoc()): ?>
  <tr>
    <td><?php echo h($row["job_ref_num"]); ?></td>
    <td><?php echo h($row["first_name"]); ?></td>
    <td><?php echo h($row["last_name"]); ?></td>
    <td><?php echo h($row["email"]); ?></td>
    <td><?php echo h($row["phone"]); ?></td>
    <td>
      <form method="post">
        <input type="hidden" name="job_ref_num" value="<?php echo h($row["job_ref_num"]); ?>">
        <select name="status">
          <option value="New" <?php if($row["status"]=="New") echo "selected"; ?>>New</option>
          <option value="Current" <?php if($row["status"]=="Current") echo "selected"; ?>>Current</option>
          <option value="Final" <?php if($row["status"]=="Final") echo "selected"; ?>>Final</option>
        </select>
        <button type="submit" name="update_status">Update</button>
      </form>
    </td>
  </tr>
  <?php endwhile; ?>
</table>

<a href="logout.php">Logout</a>
</body>
</html>
