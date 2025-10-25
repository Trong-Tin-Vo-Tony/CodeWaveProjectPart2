<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>CodeWave — Home</title>
    <link rel="icon" href="images/logo.svg" type="image/svg+xml"/>
    <link rel="stylesheet" href="styles/styles.css"/>
  </head>
  <body class="page-home">
    <?php include 'header.inc'; ?>

    <main>
      <section class="hero">
        <div class="container">
          <img class="hero-logo" src="images/logo.svg" alt="CodeWave logo" width="160" height="44"/>
          <h1>CodeWave</h1>
          <p class="slogan">"Build simply. Ship confidently."</p>
          <p class="lead">We craft cloud-ready web apps for startups and SMEs, focusing on performance, accessibility, and clean design.</p>
          <figure class="hero-figure">
            <img src="images/group-photo.svg" alt="Illustrated team working together"/>
            <figcaption>Our Melbourne team building experiences that scale.</figcaption>
          </figure>
        </div>
      </section>

      <section class="section">
        <div class="container">
          <h2>What we do</h2>
          <div class="grid grid-3">
            <section class="card">
              <h3>Threat & Vulnerability Management</h3>
              <p>Proactive defense through continuous monitoring, vulnerability scanning, and ethical hacking to identify and remediate security weaknesses before exploitation.</p>
            </section>
            <section class="card">
              <h3>Secure Cloud & Network Architecture</h3>
              <p>Designing and implementing zero-trust network models, secure-by-design cloud deployments, and robust identity and access controls (IAM) across all environments.</p>
            </section>
            <section class="card">
              <h3>Governance & Incident Response</h3>
              <p>Maintaining regulatory compliance (e.g., ISO, SOC 2, HIPAA) and providing 24/7 Rapid Incident Response and Digital Forensics to minimize breach impact and recovery time.</p>
            </section>
          </div>
        </div>
      </section>
    </main>
    <?php include 'footer.inc'; ?>
  </body>
</html>

