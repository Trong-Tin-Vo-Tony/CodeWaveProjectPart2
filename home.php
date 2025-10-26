<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>CodeWave — Innovative Cloud & Web Solutions</title>
  <link rel="icon" href="images/logo.svg" type="image/svg+xml"/>
  <link rel="stylesheet" href="styles/styles.css"/>
  <!-- <link rel="stylesheet" href="styles/home.css"/> -->


  <!-- Embedded CSS for minor inline hero gradient fix -->
  <style>
    /* Inline enhancement for hero background graphics */
    .hero {
      background: 
        linear-gradient(120deg, rgba(20,40,70,0.9), rgba(10,20,30,0.95)),
        url('images/hero-bg.svg') no-repeat center/cover;
      color: white;
      text-align: center;
    }

    .hero h1 {
      font-size: 2.5rem;
      letter-spacing: 1px;
    }

    .hero .lead {
      font-size: 1.1rem;
      margin-bottom: 1rem;
    }

    /* Small fix for buttons or inline calls to action */
    .cta-btn {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 18px;
      background: var(--brand);
      color: #fff;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: background 0.3s;
    }

    .cta-btn:hover {
      background: #377bff;
    }
  </style>
</head>

<body class='page-home'>
 <?php include 'header.inc'; ?>

  <!-- Hero Section -->
  <main>
    <section class="hero">
      <div class="container">
        <img class="hero-logo" src="images/logo.svg" alt="CodeWave company logo" width="150" height="44"/>
        <h1>Welcome to CodeWave</h1>
        <p class="slogan">“Build simply. Ship confidently.”</p>
        <p class="lead">
          CodeWave is a Melbourne-based software team dedicated to designing and developing <strong>secure, scalable, and user-friendly cloud applications</strong> for modern businesses.
        </p>

        <a href="Jobs.php" class="cta-btn">Explore Career Opportunities</a>

        <figure class="hero-figure">
          <img src="images/group-photo.jpg" alt="CodeWave team collaborating on web development project"/>
          <figcaption>Our passionate team shaping digital innovation together.</figcaption>
        </figure>
      </div>
    </section>

    <!-- What We Do Section -->
    <section class="section">
      <div class="container">
        <h2>What We Do</h2>
        <div class="grid grid-3">
          <section class="card">
            <h3>Threat & Vulnerability Management</h3>
            <p>We deliver proactive defense through 24/7 monitoring, vulnerability scanning, and ethical hacking to detect risks before they become problems.</p>
          </section>

          <section class="card">
            <h3>Secure Cloud & Network Architecture</h3>
            <p>Our engineers design zero-trust, cloud-ready infrastructures with strong encryption and identity-based access controls to ensure safety at every layer.</p>
          </section>

          <section class="card">
            <h3>Governance & Incident Response</h3>
            <p>We help clients maintain regulatory compliance and provide fast incident recovery solutions using industry-leading digital forensics methods.</p>
          </section>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <?php 
  include 'footer.inc';
  ?>

  <!-- Script for header animation -->
  <script src="scripts/animate.js"></script>
</body>
</html>