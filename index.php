<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "konexioa.php";
// ==========================
// FEEDBACK
// ==========================

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["mezua"])) {
    if (isset($_SESSION["id"])) {
        $user_id = intval($_SESSION["id"]);
        $email = null;
    } else {
        $user_id = null;
        $email = mysqli_real_escape_string($conn, $_POST["emaila"]);
    }

    $mezua = mysqli_real_escape_string($conn, $_POST["mezua"]);

    mysqli_query(
        $conn,
        "
    INSERT INTO feedback (erabiltzailea_id,email,mezua)
    VALUES (
        " .
            ($user_id ? $user_id : "NULL") .
            ",
        " .
            ($email ? "'$email'" : "NULL") .
            ",
        '$mezua'
    )
    "
    );

    echo "<div class='alert'>Eskerrik asko zure iritziagatik!</div>";
}

// karritoa iniciatzeko ez badago sortuta
if (!isset($_SESSION["carrito"])) {
    $_SESSION["carrito"] = [];
}
$total_items = count($_SESSION["carrito"]);
?>

<!DOCTYPE html>
<html lang="eu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sarrera</title>
    <link rel="stylesheet" href="style.css?v=2">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    
</head>

<body>

    <header>
        <?php include "menu.php"; ?>
    </header>

    <div class="wrapper">

        <?php include "sidebar.php"; ?>

        <main class="main-content">

            <section id="sarrera">

                <div class="slider">
                    <img src="irudiak/1.jpg" class="active" alt="Irudi 1">
                    <img src="irudiak/gu2.jpeg" alt="Irudi 2">
                </div>


                <div class="informazioa">
                    <h2>Ongi etorri gure webgunera!</h2>
                    <p>
                        Gure webgunean aurkituko dituzu azken berriak, informazio erabilgarria eta gure jardueren berri osoa.
                    </p>

                    <h3>Nor gara?</h3>
                    <p>
                        Enpresa dinamiko eta berritzaile bat gara, teknologia, zerbitzu digitalak eta komunitatearen garapena uztartzen dituena.
                    </p>

                    <h3>Zer eskaintzen dugu?</h3>
                    <p>
                        Gure produktuak eta zerbitzuak zure beharretara egokitzen dira: online ikastaroak, jarduera espezializatuak eta komunitate ekitaldiak.
                    </p>

                    <h3>Zuretzat sortua</h3>
                    <p>
                        Ez galdu gure albiste eta ekitaldi bereziak! Parte hartu gure komunitatean eta ezagutu azken berrikuntzak.
                    </p>

                    <h3>GURE LORPENAK</h3>
                    <ul>
                        <li>2023an, gure enpresak “Tokiko Berrikuntza Saria” jaso zuen gure proiektu digital berritzaileengatik.</li>
                        <li>15 herrialdetan baino gehiagotan bezeroekin lan egin dugu azken bost urteetan.</li>
                        <li>2024an, gure lantaldeak 10 aplikazio berritzaile sortu zituen, erabiltzaileen beharrak asetzeko.</li>
                        <li>50 enpresa lagun eta bazkidek baino gehiagok parte hartzen dute gure ekimenetan.</li>
                        <li>Gure zerbitzuek %40ko hazkundea izan dute azken urtean, gure bezeroen konfiantzari esker.</li>
                    </ul>
                </div>

                <aside class="berriak">
                    <h2>BERRIAK</h2>
                    <ul>
                        <li><a href="berriak.php">Udaberriko jarduera berriak martxan dira</a></li>
                        <li><a href="berriak.php">Online ikastaro berriak eskuragarri</a></li>
                        <li><a href="berriak.php">Gure komunitateko ekitaldi nagusiak</a></li>
                    </ul>
                </aside>

                <section id="feedback" class="informazioa">
                    <h2>Zure iritzia garrantzitsua da!</h2>
                    <p>Mesedez, utzi tuze iritzia edo proposamenak gure lana hobetzen jarraitzeko.</p>

                    <form method="post">
                        <?php if (!isset($_SESSION["id"])) { ?>

                        <label>Zure emaila:</label>
                        <input type="email" name="emaila" placeholder="Idatzi zure emaila" required>

                        <?php } ?>


                        <label for="mezua">Zure mezua:</label>
                        <textarea id="mezua" name="mezua" rows="5" placeholder="Idatzi hemen zure iritzia..." required></textarea>

                        <button type="submit">Bidali</button>
                    </form>
                </section>

            </section>
        </main> 
    </div> 
    <?php include "footer.php"; ?>



<script>
    $(document).ready(function () {
        const $slides = $('.slider img');
        let current = 0;
 
        setInterval(() => {
            $slides.eq(current).removeClass('active');
            current = (current + 1) % $slides.length;
            $slides.eq(current).addClass('active');
        }, 4000); // 4 segundu
    });
</script>

</body>
</html>