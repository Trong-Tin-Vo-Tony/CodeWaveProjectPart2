<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>CodeWave - About</title>
    <link rel="icon" href="images/logo.svg" type="image/svg+xml"/>
    <link rel="stylesheet" href="styles/styles.css"/>

    <style>
      img { max-width: 100%; height: auto; }
      a { color: var(--brand); }

      /* ---- Group Photo Section ---- */
      .group-photo-card {
        text-align: center;
      }

      .group-photo-container {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 10px;
      }

      .member {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 120px;
      }

      .member img {
        width: 100%;
        height: auto;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        transition: transform 0.3s ease;
      }

      .member img:hover {
        transform: scale(1.05);
      }

      .member figcaption {
        margin-top: 6px;
        font-size: 0.9rem;
        color: var(--muted);
        font-weight: 500;
      }

      .team-caption {
        font-size: 0.95rem;
        color: var(--muted);
        margin-top: 10px;
      }
    </style>
  </head>

  <body class='page-about'>
    <?php include 'header.inc'; ?>

    <main>
      <section class="section">
        <div class="container">
          <h1>About the Team</h1>

          <section class="card">
            <h2>Group Basics</h2>
            <ul>
              <li>Group name: <strong>CodeWave</strong>
                <ul><li>Class: Tuesday 2:30-4:30pm</li></ul>
              </li>
            </ul>
          </section>

          <section class="grid grid-2">
            <figure class="card group-photo-card">
              <div class="group-photo-container">
                <div class="member">
                  <img src="images/sajid.jpg" alt="Sajid Hasan">
                  <figcaption>Sajid Hasan</figcaption>
                </div>
                <div class="member">
                  <img src="images/siumi.jpg" alt="Kammantrige Siumi Sathsarani">
                  <figcaption>Kammantrige Siumi Sathsarani</figcaption>
                </div>
                <div class="member">
                  <img src="images/sandeep.jpg" alt="Sandeep Y. D. S. Anthonydura">
                  <figcaption>Sandeep Y. D. S. Anthonydura</figcaption>
                </div>
                <div class="member">
                  <img src="images/antonio.jpg" alt="Antonio Prince">
                  <figcaption>Antonio Prince</figcaption>
                </div>
                <div class="member">
                  <img src="images/trong.jpg" alt="Trong Tin Vo">
                  <figcaption>Trong Tin Vo</figcaption>
                </div>
              </div>
              <p class="team-caption">Team CodeWave - five members.</p>
            </figure>

            <section class="card">
              <h2>Contributions & Quotes</h2>
              <dl>
                <dt>Sajid Hasan</dt>
                <dd>Team Leader & Application Page Developer - "Ship small, learn fast." (Fav. language: <span lang="en">TypeScript</span>)</dd>
                <br>
                <dt>Kammantrige Siumi Sathsarani</dt>
                <dd>Home & Group Details Manager - "Design first, code clean." (Fav. language: <span lang="en">HTML</span> - Sinhala: <span lang="en"> </span>)</dd>
                <br>
                <dt>Sandeep Y. D. S. Anthonydura</dt>
                <dd>Job Apps & Position Descriptions - "Clarity beats cleverness." (First language: <span lang="en"> Sinhala </span>)</dd>
                <br>
                <dt>Antonio Prince</dt>
                <dd>Enhancements & Styling — “Details make the experience.” (Fav. language: <span lang="en">CSS</span>)</dd>
                <br>
                <dt>Trong Tin Vo</dt>
                <dd>Styling & Quality Insurance — “It'll work if you bang your head at it enough." (Fav. language: 
                  <span lang="en" style="color: orange; font-weight: bold">Python</span>)
                </dd>
              </dl>
            </section>
          </section>

          <section class="card">
            <h2>Fun Facts</h2>
            <table>
              <thead>
                <tr>
                  <th>Member</th>
                  <th>Dream job</th>
                  <th>Coding snack</th>
                  <th>Hometown</th>
                </tr>
              </thead>
              <tbody>
                <tr><td>Sajid</td><td>Platform Architect</td><td>Almonds</td><td>India</td></tr>
                <tr><td>Siumi</td><td>Software Engineer</td><td>Tim Tams</td><td>Kaluthara</td></tr>
                <tr><td>Sandeep</td><td>DevOps Engineer</td><td>Banana bread</td><td>Kandy</td></tr>
                <tr><td>Antonio</td><td>Design Engineer</td><td>Flat white</td><td>Sydney</td></tr>
                <tr><td>Tony</td><td>Entrepreneur/AI Researcher</td><td>None</td><td>Ho Chi Minh City</td></tr>
              </tbody>
            </table>
          </section>
        </div>
      </section>
    </main>

    <?php include 'footer.inc'; ?>


    <script src="scripts/animate.js"></script>
  </body>
</html>
