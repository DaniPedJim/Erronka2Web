<?php
include "konexioa.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

$erabiltzailea_id = intval($_SESSION["id"]);

/* ======================
   ORDAINDU
====================== */
if (isset($_POST["plus"])) {
    $id = intval($_POST["plus"]);

    mysqli_query(
        $conn,
        "
    UPDATE saskia 
    SET kantitatea = kantitatea + 1
    WHERE erabiltzailea_id=$erabiltzailea_id 
    AND produktua_id=$id
    "
    );
}
if (isset($_POST["minus"])) {
    $id = intval($_POST["minus"]);

    mysqli_query(
        $conn,
        "
        UPDATE saskia 
        SET kantitatea = kantitatea - 1
        WHERE erabiltzailea_id=$erabiltzailea_id 
        AND produktua_id=$id
    "
    );

    mysqli_query(
        $conn,
        "
        DELETE FROM saskia 
        WHERE kantitatea<=0
    "
    );
}
/* ======================
   SASKIKO PRODUKTUAK
====================== */

$sql = "
SELECT p.*, s.kantitatea
FROM saskia s
JOIN produktuak p ON p.id = s.produktua_id
WHERE s.erabiltzailea_id = $erabiltzailea_id
";

$result = mysqli_query($conn, $sql);

$productos = [];
$total = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $row["azpitotala"] = $row["prezioa"] * $row["kantitatea"];
    $total += $row["azpitotala"];

    $productos[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ordaindu"])) {
    // eskaera sortu
    $data = date("Y-m-d H:i:s");

    mysqli_query(
        $conn,
        "
    INSERT INTO erosketak (erabiltzailea_id,data,bidalketa_egoera)
    VALUES ($erabiltzailea_id,'$data','prestatzen')
    "
    );

    $erosketa_id = mysqli_insert_id($conn);

    // produktuak karritoan gordetzeko
    foreach ($productos as $p) {
        mysqli_query(
            $conn,
            "
        INSERT INTO erosketa_xehetasunak
        (erosketa_id,produktua_id,kantitatea,prezioa_momentuan)
        VALUES (
            $erosketa_id,
            {$p["id"]},
            {$p["kantitatea"]},
            {$p["prezioa"]}
        )
        "
        );
    }

    // karritoa baciatzeko
    mysqli_query(
        $conn,
        "DELETE FROM saskia WHERE erabiltzailea_id=$erabiltzailea_id"
    );

    header("Location: historiala.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="eu">
<head>
<meta charset="UTF-8">
<title>Saskia</title>
<link rel="stylesheet" href="style.css?v=2">
</head>

<body>

<?php include "menu.php"; ?>

    <section id="saskia">

        <h2>Zure Saskia</h2>

<?php if (empty($productos)) { ?>

<p>Zure saskia hutsik dago.</p>

<?php } else { ?>

<?php foreach ($productos as $p) { ?>

<div class="product-card">

<img src="<?php echo $p["irudia"]; ?>" width="90">

<div>
    <h3><?php echo $p["izena"]; ?></h3>
    <p>Prezioa: <?php echo number_format($p["prezioa"], 2); ?>€</p>
    <p>Kantitatea: <?php echo $p["kantitatea"]; ?></p>
    <form method="post" style="display:inline;">
    <input type="hidden" name="plus" value="<?php echo $p["id"]; ?>">
    <button>+</button>
    </form>

    <form method="post" style="display:inline;">
    <input type="hidden" name="minus" value="<?php echo $p["id"]; ?>">
    <button>-</button>
    </form>

</div>


</div>

<?php } ?>

<hr>

<p><strong>GUZTIRA: <?php echo number_format($total, 2); ?>€</strong></p>

<form method="post">
<button name="ordaindu">Ordaindu</button>
</form>

<?php } ?>

</section>

</body>
</html>
