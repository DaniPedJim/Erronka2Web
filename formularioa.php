<?php
require_once "konexioa.php";

$mensaje = "";

// formulaioa POST-etik datorrera konfirmatzeko
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recoger datos del formulario y limpiar espacios
    $izena = trim($_POST["izena"]);
    $email = trim($_POST["email"]);
    $telefonoa = trim($_POST["telefonoa"]);
    $mota = trim($_POST["mota"]);
    $mezua = trim($_POST["mezua"]);

    // konprobatzeko dena beteta dagoela
    if (empty($izena) || empty($email) || empty($telefonoa) || empty($mota) || empty($mezua)) {
        $mensaje = "Mesedez, bete formulario osoa.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Emaila ez da baliozkoa.";
    } else {
        // Kontsulta prestantzen du 
        $sql = "INSERT INTO formularioak (izena, email, telefonoa, mota, mezua) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            $mensaje = "Errorea SQL: " . $conn->error;
        } else {
            // Asociar parámetros
            $stmt->bind_param("sssss", $izena, $email, $telefonoa, $mota, $mezua);

            // ejekutatzenko
            if ($stmt->execute()) {
                // leku berdinera eramaten du bidali eta gero 
                header("Location: formularioa.php?ok=1");
                exit;
            } else {
                $mensaje = "Errorea datuak gordetzean: " . $stmt->error;
            }

            $stmt->close();
        }
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="eu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formularioa</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- MENUA -->
 <header>

        <?php include("menu.php"); ?>


    </header>

    <!-- EDUKIA -->
    <section id="formularioa">

        <?php
        // errore mensajea agertzeko
        if (isset($_GET['ok']) && $_GET['ok'] == 1) {
            echo "<p class='success'>Formularioa behar bezala bidali da!</p>";
        } elseif (!empty($mensaje)) {
            echo "<p class='error'>$mensaje</p>";
        }
        ?>

        <form method="POST" action="formularioa.php">
            <label for="izena">Izena:</label><br>
            <input type="text" id="izena" name="izena" placeholder="Zure izena edo enpresarena" required><br><br>

            <label for="email">Emaila:</label><br>
            <input type="email" id="email" name="email" placeholder="zure@email.com" required><br><br>

            <label for="telefonoa">Telefonoa:</label><br>
            <input type="tel" id="telefonoa" name="telefonoa" placeholder="Adibidez: 666123456" required><br><br>

            <label for="mota">Zer motatako ekipo edo osagaiak emango dituzu?</label><br>
            <select id="mota" name="mota" required>
                <option value="">Aukeratu</option>
                <option value="ordenagailu_berezi">Ordenagailu osatua</option>
                <option value="portatil">Portatil</option>
                <option value="osagaiak">Osagaiak (RAM, CPU, Grafiko, etab.)</option>
                <option value="beste">Bestebat</option>
            </select><br><br>

            <label for="mezua">Mezua:</label><br>
            <textarea id="mezua" name="mezua"
                placeholder="Mesedez, gehitu informazio gehigarria, egoera, kopurua, etab." required></textarea><br><br>

            <button type="submit">Bidali</button>
        </form>
    </section>

    <!-- FOOTER -->
    <?php include "footer.php"; ?>

</body>

</html>
