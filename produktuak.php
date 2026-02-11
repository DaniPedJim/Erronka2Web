<?php
include "konexioa.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$kantitatea = 0;

if (isset($_SESSION["id"])) {
    $erabiltzailea_id = $_SESSION["id"];

    $res = mysqli_query(
        $conn,
        "SELECT SUM(kantitatea) AS total FROM saskia WHERE erabiltzailea_id=$erabiltzailea_id"
    );
    $fila = mysqli_fetch_assoc($res);

    $kantitatea = $fila["total"] ?? 0;
}

// erosi botoia
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["product_id"])) {
    if (!isset($_SESSION["id"])) {
        header("Location: login.php");
        exit();
    }

    $produktua_id = intval($_POST["product_id"]);
    $erabiltzailea_id = $_SESSION["id"];

    // konprobatzeko karritoamnm produktua dagoen 
    $check = mysqli_query(
        $conn,
        "SELECT * FROM saskia 
        WHERE erabiltzailea_id=$erabiltzailea_id 
        AND produktua_id=$produktua_id"
    );

    if (mysqli_num_rows($check) > 0) {
        // produktua badago +1
        mysqli_query(
            $conn,
            "UPDATE saskia 
            SET kantitatea = kantitatea + 1
            WHERE erabiltzailea_id=$erabiltzailea_id 
            AND produktua_id=$produktua_id"
        );
    } else {
        // ez badago insert egingo du
        mysqli_query(
            $conn,
            "INSERT INTO saskia (erabiltzailea_id, produktua_id, kantitatea)
            VALUES ($erabiltzailea_id, $produktua_id, 1)"
        );
    }
}
?>
<!DOCTYPE html>
<html lang="eu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produktuak</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header>
   

    <?php include "menu.php"; ?>
     
</header>
<div class="wrapper">

    <?php include "sidebar.php"; ?>

    <main class="main-content">

        <section id="produktuak">

            <form method="get" class="filtro-form">
                <label for="mota">Iragazi motaren arabera:</label>
                <select name="mota" id="mota" onchange="this.form.submit()">
                    <option value="">Guztiak</option>
                    <?php
                    $sql_motak = "SELECT DISTINCT mota FROM produktuak WHERE egoera='Ikusgai'";
                    $res_motak = mysqli_query($conn, $sql_motak);

                    while ($m = mysqli_fetch_assoc($res_motak)) {
                        $selected = (isset($_GET['mota']) && $_GET['mota'] == $m['mota']) ? 'selected' : '';
                        echo "<option value='{$m['mota']}' $selected>{$m['mota']}</option>";
                    }
                    ?>
                </select>
            </form>

    <div class="grid">

        <?php
        $sql = "SELECT * FROM produktuak WHERE egoera = 'Ikusgai'";

        if (!empty($_GET["mota"])) {
            $mota = mysqli_real_escape_string($conn, $_GET["mota"]);
            $sql .= " AND mota = '$mota'";
        }

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="produktu">
<img src="<?php echo $row["irudia"]; ?>" 
     alt="<?php echo $row["izena"]; ?>" 
     class="producto-img">

                    <h3><?php echo $row["izena"]; ?></h3>

                   <p>
                            <?= $row["mota"] ?> •
                            <?= $row["modeloa"] ?> •
                            <?= $row["konektibitatea"] ?>
                        </p>

                    <p class="prezioa">
                        Prezioa: <?php echo number_format(
                            $row["prezioa"],
                            2
                        ); ?>€
                    </p>

                    <?php if (isset($_SESSION["id"])) { ?>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo $row[
                                "id"
                            ]; ?>">
                            <button type="submit" class="erosi" data-erosi>Erosi</button>
                        </form>
                    <?php } ?>
                </div>
        <?php }
        } else {
            echo "<p>Ez dago produkturik.</p>";
        }
        ?>

    </div>
</section>

<!-- FOOTER -->

<script src="saskirako-animazioa.js"></script>
    <?php include "footer.php"; ?>

</body>
</html>
