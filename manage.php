<?php
// manage.php — HR Manager dashboard (Requirement 6)
require_once 'auth.php';       // blocks access if not logged in
require_once 'settings.php';

$conn = @mysqli_connect($host2, $user, $pwd, $sql_db, $port2);
if (!$conn) { 
  $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
  if (!$conn) {
    die('Database connection failed'); 
  }
}

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// ---------- Sorting (whitelist) ----------
$validSort = ['EOInumber','jobRef','firstName','lastName', 'streetAddress', 'suburbTown', 'state', 'postcode', 'email','phone', 'skills', 'otherComments', 'status'];
$sort = $_GET['sort'] ?? 'EOInumber';
$order  = $_GET['order']  ?? 'DESC';
$sort = in_array($sort, $validSort, true) ? $sort : 'EOInumber';
$order  = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

// ---------- POST actions with CSRF ----------
$flash = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
    http_response_code(403);
    die('Invalid CSRF token');
  }

  // Update status
  if (($_POST['action'] ?? '') === 'update_status') {
    $eoi = (int)($_POST['EOInumber'] ?? 0);
    $status  = $_POST['status'] ?? '';
    $ok  = ['New','Current','Final'];
    if ($eoi > 0 && in_array($status, $ok, true)) {
      $stmt = $conn->prepare("UPDATE eoi SET status=? WHERE EOInumber=?");
      $stmt->bind_param('si', $status, $eoi);
      $stmt->execute();
      $flash = $stmt->affected_rows >= 0 ? "EOI #$eoi status updated to $status." : "No change made.";
    } else {
      $flash = 'Invalid status update request.';
    }
  }

  if (($_POST['action'] ?? '') === 'delete_by_jobref') {
    $jobref = (int)($_POST['job_ref_num'] ?? 0);
    if ($jobref > 0) {
      $stmt = $conn->prepare("DELETE FROM eoi WHERE jobRef = ?");
      $stmt->bind_param('i', $jobref);
      $stmt->execute();
      $flash = "Deleted {$stmt->affected_rows} EOI record(s) for Job Ref ".h($jobref).".";
    } else {
      $flash = 'Provide a valid numeric job reference.';
    }
  }

  // PRG pattern
  $qs = $_GET;
  header('Location: manage.php?'.http_build_query($qs));
  exit;
}

$where  = [];
$types  = '';
$params = [];

// $jobSearch = trim($_GET['job_ref_num'] ?? '');
// $fnSearch  = trim($_GET['first_name'] ?? '');
// $lnSearch  = trim($_GET['last_name'] ?? '');

// if ($jobSearch !== '') {
//   $where[]  = "job_ref_num = ?";
//   $types   .= 'i';
//   $params[] = (int)$jobSearch;
// }
// if ($fnSearch !== '') {
//   $where[]  = "first_name LIKE CONCAT('%', ?, '%')";
//   $types   .= 's';
//   $params[] = $fnSearch;
// }
// if ($lnSearch !== '') {
//   $where[]  = "last_name LIKE CONCAT('%', ?, '%')";
//   $types   .= 's';
//   $params[] = $lnSearch;
// }

$sql = "SELECT EOInumber,jobRef,firstName,lastName, streetAddress, suburbTown, state, postcode, email, phone, skills, otherComments, status FROM eoi";
// if ($where) $sql .= " WHERE ".implode(' AND ', $where);
$sql .= " ORDER BY $sort $order";

$stmt = $conn->prepare($sql);
if ($types !== '') $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Manage EOIs</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="styles/styles.css">
  <link rel="stylesheet" href="styles/admin.css">
</head>
<body>

<?php include 'header.inc'; ?>

<main class="container">
  <header class="manage">
    <h1>HR Manager — EOI Management</h1>
    <div class="right">
      Logged in as <span class="badge"><?= h($_SESSION['username'] ?? 'Manager') ?></span>
      · <a href="logout.php">Logout</a>
    </div>
  </header>

  <?php if ($flash): ?><div class="flash"><?= h($flash) ?></div><?php endif; ?>

  <form class="topbar" method="get" action="">
    <fieldset>
      <legend>Sort</legend>
          <label>Sort</label>
          <select name="sort">
            <?php foreach ($validSort as $f): ?>
              <option value="<?= $f ?>" <?= $sort===$f?'selected':'' ?>><?= $f ?></option>
            <?php endforeach; ?>
          </select>
          <select name="order">
            <option value="ASC"  <?= $order==='ASC'?'selected':'' ?>>ASC</option>
            <option value="DESC" <?= $order==='DESC'?'selected':'' ?>>DESC</option>
          </select>
        </div>
        <br/>
        <br/>
        <div style="align-self:end">
          <button type="submit">Apply</button>
        </div>
      </div>
    </fieldset>
  </form>

  <form class="topbar" method="post" action=""
        onsubmit="return confirm('Delete ALL EOIs for this Job Reference? This cannot be undone.');">
    <fieldset class="danger">
      <legend>Bulk Delete by Job Reference</legend>
      <input type="hidden" name="csrf" value="<?= h($_SESSION['csrf'] ?? '') ?>">
      <input type="hidden" name="action" value="delete_by_jobref">
      <div class="controls">
        <div>
          <label>Job Reference</label>
          <input type="number" name="job_ref_num" required>
        </div>
        <div style="align-self:end">
          <button type="submit">Delete EOIs</button>
        </div>
      </div>
    </fieldset>
  </form>

  <table>
    <thead>
      <tr>
        <th>EOI #</th>
        <th>Job Ref</th>
        <th>First</th>
        <th>Last</th>
        <th>Street Address</th>
        <th>Suburb/Town</th>
        <th>State</th>
        <th>Post</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Skills</th>
        <th>Other Skills</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= h($row['EOInumber']) ?></td>
        <td><?= h($row['jobRef']) ?></td>
        <td><?= h($row['firstName']) ?></td>
        <td><?= h($row['lastName']) ?></td>
        <td><?= h($row['streetAddress']) ?></td>
        <td><?= h($row['suburbTown']) ?></td>
        <td><?= h($row['state']) ?></td>
        <td><?= h($row['postcode']) ?></td>
        <td><?= h($row['email']) ?></td>
        <td><?= h($row['phone']) ?></td>
        <td><?= h($row['skills']) ?></td>
        <td><?= h($row['otherComments']) ?></td>
        <td class="status-<?= h($row['status']) ?>">
          <form method="post" class="inline-form" action="">
            <input type="hidden" name="csrf" value="<?= h($_SESSION['csrf'] ?? '') ?>">
            <input type="hidden" name="action" value="update_status">
            <input type="hidden" name="EOInumber" value="<?= h($row['EOInumber']) ?>">
            <select name="status">
              <?php foreach (['New','Current','Final'] as $s): ?>
                <option value="<?= $s ?>" <?= $row['status']===$s?'selected':'' ?>><?= $s ?></option>
              <?php endforeach; ?>
            </select>
            <button type="submit">Update</button>
          </form>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</main>

<?php if (file_exists('footer.inc')) include 'footer.inc'; ?>
</body>
</html>