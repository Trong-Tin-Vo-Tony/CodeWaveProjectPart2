<?php
session_start();

$errors = isset($_SESSION['eoi_errors']) ? $_SESSION['eoi_errors'] : [];
$formData = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];


unset($_SESSION['eoi_errors']);
unset($_SESSION['form_data']);


function sticky($field, $formData) {
    if (isset($formData[$field])) {
        
        return 'value="' . htmlspecialchars($formData[$field]) . '"';
    }
    return '';
}


function checked($field, $value, $formData) {
    
    if ($field == 'skills' && isset($formData[$field]) && in_array($value, $formData[$field])) {
        return 'checked';
    }
    return '';
}


function selected($field, $value, $formData) {
    if (isset($formData[$field]) && $formData[$field] == $value) {
        return 'selected';
    }
    return '';
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Apply for a Job</title>
  <link rel="icon" href="images/logo.svg" type="image/svg+xml" />

  <link rel="stylesheet" href="styles/styles.css"/>
  <!-- <link rel="stylesheet" href="styles/apply.css"/> -->
  <!-- <link rel="stylesheet" href="styles/styles.css"/> -->

</head>

<body class="page-apply">
<?php require_once('header.inc'); ?>

<main class="main_container">
    <header>
      <h1>Apply for a Job</h1>
      <p>Please complete all required fields. Data validation is performed on the server.</p>
    </header>

    <?php if (count($errors) > 0): ?>
        <div class='message error-list'>
            <p>❌ **Application Failed Validation!** Please fix the following errors:</p>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form class="app-form" method="post" action="process_eoi.php" autocomplete="on">
      <div class="field">
        <label for="ref">Job reference number</label>
        <input id="ref" name="ref" type="text" <?php echo sticky('ref', $formData); ?> />
      </div>

      <div class="field">
        <label for="firstName">First name</label>
        <input id="firstName" name="firstName" type="text" <?php echo sticky('firstName', $formData); ?> />
      </div>

      <div class="field">
        <label for="lastName">Last name</label>
        <input id="lastName" name="lastName" type="text" <?php echo sticky('lastName', $formData); ?> />
      </div>

      <div class="field">
        <label for="street">Street Address</label>
        <input id="street" name="street" type="text" <?php echo sticky('street', $formData); ?> />
      </div>

      <div class="field">
        <label for="suburb">Suburb/Town</label>
        <input id="suburb" name="suburb" type="text" <?php echo sticky('suburb', $formData); ?> />
      </div>

      <div class="field">
        <label for="state">State</label>
        <select id="state" name="state">
          <option value="" selected disabled>Choose state</option>
          <option <?php echo selected('state', 'VIC', $formData); ?>>VIC</option>
          <option <?php echo selected('state', 'NSW', $formData); ?>>NSW</option>
          <option <?php echo selected('state', 'QLD', $formData); ?>>QLD</option>
          <option <?php echo selected('state', 'NT', $formData); ?>>NT</option>
          <option <?php echo selected('state', 'WA', 'SA', $formData); ?>>WA</option>
          <option <?php echo selected('state', 'SA', $formData); ?>>SA</option>
          <option <?php echo selected('state', 'TAS', $formData); ?>>TAS</option>
          <option <?php echo selected('state', 'ACT', $formData); ?>>ACT</option>
        </select>
      </div>

      <div class="field">
        <label for="postcode">Postcode</label>
        <input id="postcode" name="postcode" type="text" <?php echo sticky('postcode', $formData); ?> />
      </div>

      <div class="field">
        <label for="email">Email</label>
        <input id="email" name="email" type="text" <?php echo sticky('email', $formData); ?> />
      </div>

      <div class="field">
        <label for="phone">Phone number</label>
        <input id="phone" name="phone" type="tel" <?php echo sticky('phone', $formData); ?> />
      </div>

      <fieldset class="field">
        <legend>Skill list</legend>
        <label><input type="checkbox" name="skills[]" value="Networking" <?php echo checked('skills', 'Networking', $formData); ?>/> Networking</label>
        <label><input type="checkbox" name="skills[]" value="Ethical Hacking" <?php echo checked('skills', 'Ethical Hacking', $formData); ?>/> Ethical hacking</label>
        <label><input type="checkbox" name="skills[]" value="Digital Forensics" <?php echo checked('skills', 'Digital Forensics', $formData); ?>/> Digital forensics</label>
        <label><input type="checkbox" name="skills[]" value="Risk Management" <?php echo checked('skills', 'Risk Management', $formData); ?>/> Risk management</label>
        <label><input type="checkbox" name="skills[]" value="SIEM" <?php echo checked('skills', 'SIEM', $formData); ?>/> SIEM monitoring</label>
        <label><input type="checkbox" name="skills[]" value="Other" <?php echo checked('skills', 'Other', $formData); ?>/> Other skills…</label>
      </fieldset>

      <div class="field">
        <label for="otherSkills">Other skills</label>
        <textarea id="otherSkills" name="otherSkills" rows="3">
            <?php echo isset($formData['otherSkills']) ? htmlspecialchars($formData['otherSkills']) : ''; ?>
        </textarea>
      </div>

      <button type="submit" class="apply-btn">Submit Application</button>
    </form>


</main>
</main>

<?php require_once('footer.inc'); ?>
</body>
</html>
