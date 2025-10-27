<?php
// connect to database
require_once "settings.php"; 
$conn = mysqli_connect($host, $user, $pwd, $sql_db);

// if connection fails
if (!$conn) {
  die("<p>Database connection failed: " . mysqli_connect_error() . "</p>");
}

// get all jobs from table
$query = "SELECT * FROM jobs";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="images/logo.svg" type="image/svg+xml" />

  <link rel="stylesheet" href="styles/base.css" />
  <link rel="stylesheet" href="styles/nav-bar.css" />
  <link rel="stylesheet" href="styles/Jobs.css" />

  <title>CodeWave Jobs</title>
</head>
<body>

  <!-- HEADER -->
  <header class="site-header">
    <div class="container header-inner">
      <a class="brand" href="index.php" aria-label="CodeWave home">
        <img src="images/logo.svg" alt="CodeWave logo" id="nav-bar-logo" height="32" />
      </a>
      <nav aria-label="Main">
        <ul class="nav">
          <li><a href="home.php">Home</a></li>
          <li><a class="active" href="jobs.php">Jobs</a></li>
          <li><a href="about.php">About</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <img src="images/cyber-security.png" alt="Cyber security banner" class="image" loading="lazy" />

  <main class="container">
    <h1>CodeWave Career Opportunities</h1>
    <p><b>Apply now for exciting opportunities in Cyber Security. Join our team in protecting global systems and data.</b></p>
    <hr />

    <section id="position-descriptions" aria-labelledby="jobs-heading">
      <h2 id="jobs-heading">Current Openings — Cyber Security Roles</h2>
      <p>Below are the roles currently available. Each position includes its reference number, salary range, summary, duties, and requirements.</p>

      <?php
      // display each job dynamically
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<article class='job'>";
        echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
        echo "<p class='ref'>Ref: " . htmlspecialchars($row['jobRef']) . "</p>";
        echo "<p class='salary'>Salary: " . htmlspecialchars($row['salary']) . "</p>";
        echo "<p><strong>Mode of Employment:</strong> " . htmlspecialchars($row['employmentType']) . "<br />";
        echo "<strong>Reports to:</strong> " . htmlspecialchars($row['reportsTo']) . "</p>";
        echo "<p><strong>Summary:</strong> " . htmlspecialchars($row['summary']) . "</p>";

        // duties list
        echo "<h4>Duties & Responsibilities</h4><ol>";
        $duties = explode('|', $row['duties']);
        foreach ($duties as $duty) {
          echo "<li>" . htmlspecialchars($duty) . "</li>";
        }
        echo "</ol>";

        // essential & preferable requirements
        echo "<aside>";
        echo "<h4>Essential Requirements</h4><ul>";
        $ess = explode('|', $row['essentialReq']);
        foreach ($ess as $e) {
          echo "<li>" . htmlspecialchars($e) . "</li>";
        }
        echo "</ul>";

        echo "<h4>Preferable Requirements</h4><ul>";
        $pref = explode('|', $row['preferableReq']);
        foreach ($pref as $p) {
          echo "<li>" . htmlspecialchars($p) . "</li>";
        }
        echo "</ul>";
        echo "</aside>";

        echo "</article>";
      }

      mysqli_close($conn);
      ?>
    </section>
  </main>

  <div class="btn-container">
    <a href="apply.php" class="apply-btn">Apply Now</a>
  </div>

  <footer class="site-footer">
    <div class="container footer-inner">
      <p>&copy; CodeWave Pty Ltd — COS10026</p>
    </div>
  </footer>

</body>
</html>
