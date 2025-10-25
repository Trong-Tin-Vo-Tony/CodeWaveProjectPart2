<?php
session_start();


if (isset($_SESSION['eoi-render-info'])) {
    
   
    $eoi_render_info = $_SESSION['eoi-render-info'];
    

    unset($_SESSION['eoi-render-info']);
    
} else {
    
    header("Location: apply.php");
    exit();
}

session_write_close();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted</title>
    <link rel="icon" href="images/logo.svg" type="image/svg+xml"/>
    <link rel="stylesheet" href="styles/styles.css"/>
  </head>

  <body class="page-thankyou">
    <main class="page-wrapper">
      <div class="thankyou-container">
        
        <h1>🎉 Thank You!</h1>
        <p>Your application has been successfully submitted. Our cybersecurity recruitment team will review it and get back to you soon.</p>
        
        <?php echo $eoi_render_info; ?>

        <a href="home.php" class="home-btn">Return to Home</a>

      </div>
    </main>

    <?php  ?>
  </body>
</html>
