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
    </style>
  </head>

  <body>
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
            <figure class="card">
              <img src="images/group-photo.svg" alt="Team CodeWave group photo with five members"/>
              <figcaption>Team CodeWave - five members.</figcaption>
            </figure>

            <section class="card">
              <h2>Contributions & Quotes</h2>
              <dl>
                <dt>Sajid Hasan (MIA)</dt>
                <dd>Team Leader & Application Page Developer - "Ship small, learn fast." (Fav. language: <span lang="en">TypeScript</span>)</dd>

                <dt>Kammantrige Siumi Sathsarani</dt>
                <dd>Home & Group Details Manager - "Design first, code clean." (Fav. language: <span lang="en">HTML</span> - Sinhala: <span lang="en"> </span>)</dd>

                <dt>Sandeep Y. D. S. Anthonydura</dt>
                <dd>Job Apps & Position Descriptions - "Clarity beats cleverness." (First language: <span lang="en"> Sinhala </span>)</dd>

                <dt>Antonio Prince</dt>
                <dd>Enhancements & Styling — “Details make the experience.” (Fav. language: <span lang="en">CSS</span>)</dd>

                <dt>Trong Tin Vo</dt>
                <dd>Styling & Quality Insurance — “It'll work if you bang your head at it enough." (Fav. language: <span lang="en", style="color: orange; font-weight: bold">Python</span>)</dd>
              </dl>
            </section>
          </section>

          <section class="card">
            <h2>Fun Facts</h2>
            <table>
              <thead>
                <tr><th>Member</th><th>Dream job</th><th>Coding snack</th><th>Hometown</th></tr>
              </thead>
              <tbody>
                <tr><td>Sajid</td><td>Platform Architect</td><td>Almonds</td><td>Colombo</td></tr>
                <tr><td>Siumi</td><td>Front‑end Engineer</td><td>Tim Tams</td><td>Melbourne</td></tr>
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

  </body>
</html>