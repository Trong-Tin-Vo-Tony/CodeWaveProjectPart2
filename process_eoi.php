<?php
session_start();
// Include database settings
require_once('settings.php');

// --- 1. BLOCK DIRECT ACCESS ---
// Check if the request method is POST and if a required field is set.
if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST['ref'])) {
    header("Location: apply.php");
    exit();
}

// --- 2. CONNECT TO DATABASE ---
$conn = @mysqli_connect($host2, $user, $pwd, $sql_db, $port2);
if (!$conn) {
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
    if (!$conn) {
        // Show a general error, do not expose internal details like mysqli_connect_error()
        die("<p>Database connection failure. Please try again later.</p>");
    }
}

// --- 3. SERVER-SIDE VALIDATION & SANITISING ---
$errors = [];

// Get and sanitize all inputs
$jobRef = mysqli_real_escape_string($conn, trim($_POST['ref']));
$firstName = mysqli_real_escape_string($conn, trim($_POST['firstName']));
$lastName = mysqli_real_escape_string($conn, trim($_POST['lastName']));
$street = mysqli_real_escape_string($conn, trim($_POST['street']));
$suburb = mysqli_real_escape_string($conn, trim($_POST['suburb']));
$state = mysqli_real_escape_string($conn, trim($_POST['state']));
$postcode = mysqli_real_escape_string($conn, trim($_POST['postcode']));
$email = mysqli_real_escape_string($conn, trim($_POST['email']));
$phone = mysqli_real_escape_string($conn, trim($_POST['phone']));

// Handle skills array to string conversion
$skillsArray = $_POST['skills'] ?? [];
$skills = mysqli_real_escape_string($conn, implode(", ", $skillsArray));
$otherSkills = mysqli_real_escape_string($conn, trim($_POST['otherSkills']));

// Replicate and strengthen the validation rules from apply.php
if (!preg_match("/^[A-Za-z0-9]{5}$/", $jobRef)) $errors[] = "Job reference must be 5 alphanumeric characters.";
if (!preg_match("/^[A-Za-z\s\-]{1,20}$/", $firstName)) $errors[] = "First name invalid (max 20 chars, letters/spaces/hyphens only)."; // Added spaces/hyphens for real names
if (!preg_match("/^[A-Za-z\s\-]{1,20}$/", $lastName)) $errors[] = "Last name invalid (max 20 chars, letters/spaces/hyphens only).";
if (empty($street) || strlen($street) > 40) $errors[] = "Street address is required and must be max 40 characters.";
if (empty($suburb) || strlen($suburb) > 40) $errors[] = "Suburb/Town is required and must be max 40 characters.";
if (!in_array($state, ['VIC', 'NSW', 'QLD', 'NT', 'WA', 'SA', 'TAS', 'ACT'])) $errors[] = "Invalid state selected.";
if (!preg_match("/^[0-9]{4}$/", $postcode)) $errors[] = "Postcode must be 4 digits.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";
if (!preg_match("/^[0-9]{8,12}$/", $phone)) $errors[] = "Phone number must be 8–12 digits.";
if (empty($skills) && empty($otherSkills)) $errors[] = "You must select at least one skill or describe other skills.";
// empty() -> bool

// If validation fails, redirect back to apply.php
if (count($errors) > 0) {
    // Store errors in session to display them on apply.php
    $_SESSION['eoi_errors'] = $errors;
    // Optionally store form data to repopulate fields
    $_SESSION['form_data'] = $_POST;
    mysqli_close($conn);
    header("Location: apply.php");
    exit();
}

// --- 4. CREATE EOI TABLE (if it does not exist) ---
$tableName = "eoi";

// SQL to create the table based on the implied structure, adding EOInumber and status
$createTableQuery = "
    CREATE TABLE IF NOT EXISTS $tableName (
        EOInumber INT AUTO_INCREMENT PRIMARY KEY,
        jobRef CHAR(5) NOT NULL,
        firstName VARCHAR(20) NOT NULL,
        lastName VARCHAR(20) NOT NULL,
        streetAddress VARCHAR(40) NOT NULL,
        suburbTown VARCHAR(40) NOT NULL,
        state CHAR(3) NOT NULL,
        postcode CHAR(4) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(12) NOT NULL,
        skills TEXT NOT NULL,
        otherComments TEXT,
        status ENUM('New', 'Current', 'Final') DEFAULT 'New'
    );
";

# mysqli_query() -> bool
if (!mysqli_query($conn, $createTableQuery)) {
    die("Error creating table: " . mysqli_error($conn));
}

// --- 5. INSERT RECORD using Prepared Statement (Security) ---
// Prepared statements prevent SQL injection.
$sql = "INSERT INTO $tableName (
    jobRef, firstName, lastName, streetAddress, suburbTown, state, postcode, email, phone, skills, otherComments
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

// Bind parameters: 's' for string ( used 's' for everything here)
mysqli_stmt_bind_param(
    $stmt, "sssssssssss",
    $jobRef, $firstName, $lastName, $street, $suburb, $state, $postcode, $email, $phone, $skills, $otherSkills
);

$success = mysqli_stmt_execute($stmt);
$EOInumber = false;

if ($success) {
    // Get the auto-generated EOInumber
    $EOInumber = mysqli_insert_id($conn);
} else {
    // Handle insertion error
    die("<p>Error submitting EOI: " . mysqli_error($conn) . "</p>");
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

if ($EOInumber) {
    $_SESSION["eoi-render-info"] = '
<main class="main_container">
    <section class="confirmation">
        <p>✅ **Success!** Your Expression of Interest has been successfully submitted.</p>
        <p>Your unique **EOI Number** is: <strong class="eoi-number">' . htmlspecialchars($EOInumber) . '</strong></p>
        <p>We recommend you save this number for future reference.</p>
        <p><a href="jobs.php" class="back-link">View other jobs</a> | <a href="index.php" class="back-link">Return to Home Page</a></p>
    </section>
</main>';
}
else {
    $_SESSION['eoi-render-info'] = '
    <section class="error-message">
        <p>❌ **Error:** Could not process your application. Please check your form submission and try again.</p>
        <p><a href="apply.php" class="back-link">Go back to the application form</a></p>
    </section>';
}

header("Location: thankyou.php");
exit

?>