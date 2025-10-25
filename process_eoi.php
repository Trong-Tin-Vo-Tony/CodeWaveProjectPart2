<?php
session_start();

// --- 1. ACCESS CONTROL ---
// Redirect if accessed directly (must be a POST request and have the 'ref' field)
if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST['ref'])) {
    header("Location: apply.php");
    exit();
}

require_once('settings.php'); // Contains $host, $user, $pwd, $sql_db

// Store all POST data for sticky form (in case of failure)
$formData = $_POST;
$errors = [];

// --- 2. DATABASE CONNECTION ---
// The connection details are in settings.php
$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    // For production, you wouldn't die() but log the error and redirect.
    die("<h1>Database Connection Failure</h1><p>Connection failed: " . mysqli_connect_error() . "</p>");
}

// --- 3. INPUT RETRIEVAL, SANITIZATION, AND VALIDATION ---

// Retrieve and sanitize all inputs by trimming whitespace
$jobRef = trim($_POST['ref']);
$firstName = trim($_POST['firstName']);
$lastName = trim($_POST['lastName']);
$street = trim($_POST['street']);
$suburb = trim($_POST['suburb']);
$state = trim($_POST['state']);
$postcode = trim($_POST['postcode']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
// Skills needs special handling as it comes as an array
$skillsArray = isset($_POST['skills']) ? $_POST['skills'] : [];
$skills = implode(", ", $skillsArray); // Store as comma-separated string
$otherSkills = trim($_POST['otherSkills']);


// --- VALIDATION CHECKS (Based on required project rules) ---
if (!preg_match("/^[A-Za-z0-9]{5}$/", $jobRef)) {
    $errors[] = "Job reference must be exactly 5 letters and/or numbers.";
}

if (empty($firstName) || !preg_match("/^[A-Za-z]{1,20}$/", $firstName)) {
    $errors[] = "First name must contain 1-20 letters only.";
}

if (empty($lastName) || !preg_match("/^[A-Za-z]{1,20}$/", $lastName)) {
    $errors[] = "Last name must contain 1-20 letters only.";
}

if (empty($street) || strlen($street) > 40) {
    $errors[] = "Street address is required and cannot exceed 40 characters.";
}

if (empty($suburb) || strlen($suburb) > 40) {
    $errors[] = "Suburb/Town is required and cannot exceed 40 characters.";
}

$valid_states = ['VIC', 'NSW', 'QLD', 'NT', 'WA', 'SA', 'TAS', 'ACT'];
if (empty($state) || !in_array($state, $valid_states)) {
    $errors[] = "Please select a valid State from the list.";
}

if (!preg_match("/^[0-9]{4}$/", $postcode)) {
    $errors[] = "Postcode must be exactly 4 digits.";
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "A valid email address is required.";
}

if (!preg_match("/^[0-9]{8,12}$/", $phone)) {
    $errors[] = "Phone number must be 8–12 digits only.";
}

if ($otherSkills != "" && strlen($otherSkills) > 500) {
    $errors[] = "Other skills/comments cannot exceed 500 characters.";
}


// --- 4. HANDLE VALIDATION FAILURE ---
if (count($errors) > 0) {
    $_SESSION['eoi_errors'] = $errors;
    $_SESSION['form_data'] = $formData; // Store all data for sticky form
    mysqli_close($conn);
    header("Location: apply.php");
    exit();
}


// --- 5. DATABASE SETUP AND SECURE INSERTION ---

// A. Create table if not exists (Ensures self-healing setup for the marker)
$create_table_sql = "
    CREATE TABLE IF NOT EXISTS eoi (
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
        skills VARCHAR(255),
        otherComments TEXT,
        dateApplied TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status ENUM('New', 'Current', 'Final') DEFAULT 'New'
    )
";
if (!mysqli_query($conn, $create_table_sql)) {
    // If table creation fails, store error and redirect.
    $errors[] = "Database table creation failed: " . mysqli_error($conn);
    $_SESSION['eoi_errors'] = $errors;
    $_SESSION['form_data'] = $formData;
    mysqli_close($conn);
    header("Location: apply.php");
    exit();
}

// B. Secure Insertion using Prepared Statements
$sql = "INSERT INTO eoi (jobRef, firstName, lastName, streetAddress, suburbTown, state, postcode, email, phone, skills, otherComments)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

// Check if the statement prepared correctly
if ($stmt === false) {
    $errors[] = "SQL Prepare failed: " . mysqli_error($conn);
    $_SESSION['eoi_errors'] = $errors;
    $_SESSION['form_data'] = $formData;
    mysqli_close($conn);
    header("Location: apply.php");
    exit();
}

// Bind parameters: 'sssssssssss' means all 11 fields are treated as strings
mysqli_stmt_bind_param($stmt, "sssssssssss", $jobRef, $firstName, $lastName, $street, $suburb, $state, $postcode, $email, $phone, $skills, $otherSkills);


// --- 6. EXECUTE AND HANDLE REDIRECTION (WITH EOI NUMBER CAPTURE) ---
if (mysqli_stmt_execute($stmt)) {
    
    // CAPTURE THE UNIQUE EOI NUMBER
    // mysqli_insert_id() retrieves the auto-generated Primary Key (EOInumber)
    $eoi_id = mysqli_insert_id($conn);

    // PREPARE AND STORE THE CONFIRMATION MESSAGE FOR thankyou.php
    $eoi_render_info = "<p>Your unique EOI number is: <strong>$eoi_id</strong></p>";
    
    // Store the message in the session variable for thankyou.php to pick up
    $_SESSION['eoi-render-info'] = $eoi_render_info;

    // CLEAN UP AND REDIRECT
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    header("Location: thankyou.php");
    exit();

} else {
    // EXECUTION FAILURE (e.g., database locked, transient issue)
    $errors[] = "Database insertion failed: " . mysqli_error($conn);
    
    // Store errors and redirect back to apply.php
    $_SESSION['eoi_errors'] = $errors;
    $_SESSION['form_data'] = $formData;
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: apply.php");
    exit();
}
?>
