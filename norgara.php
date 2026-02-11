<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("konexioa.php");

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}
$total_items = count($_SESSION['carrito']);
?>
<!DOCTYPE html>
<html lang="eu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nor Gara</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- MENUA -->
   <header>

        <?php include("menu.php"); ?>


    </header>

    <!-- EDUKIA -->
    <section id="norgara">
        <h1>NOR GARA</h1>

        <div class="content">
            <div class="testua">
                <h2>Gure Historia</h2>
                <p>
                    Gure enpresa 2010ean sortu zen, teknologia berrien merkatuan berrikuntza
                    eta kalitatea eskaintzeko asmoz. Ordutik, ehunka bezero pozik ditugu
                    eta gure produktu eta zerbitzuen bidez komunitate teknologikoan erreferente bihurtu gara.
                </p>

                <h2>Gure Balioak</h2>
                <ul class="balioak">
                    <li>Berrikuntza</li>
                    <li>Kalitatea</li>
                    <li>Bezeroarentzako arreta</li>
                </ul>
            </div>

            <div class="irudia">
                <img src="irudiak/gu.jpg" alt="Gure taldea">
            </div>
        </div>

        <div class="maps">
            <h2>Aurki gaitzazu hemen</h2>
            <iframe src="https://www.google.com/maps?q=Arranomendia+2+20240+Ordizia+Gipuzkoa&hl=es&z=16&output=embed"
                width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

    </section>


    <!-- FOOTER -->
        <?php include "footer.php"; ?>


</body>

</html>