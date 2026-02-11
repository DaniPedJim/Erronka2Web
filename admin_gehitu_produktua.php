<?php
session_start();
require_once "konexioa.php";

/* Administradorea dela ikusteko  */
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administratzailea') {
    header("Location: index.php");
    exit;
}

/* Formulario bidali */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $izena = $_POST['izena'];
    $kategoria = $_POST['kategoria'];
    $mota = $_POST['mota'];
    $modeloa = $_POST['modeloa'];
    $prezioa = $_POST['prezioa'];
    $konektibitatea = $_POST['konektibitatea'];
    $egoera = $_POST['egoera'];
    $stock = $_POST['stock'];

    $irudiBidea = null;

    if (isset($_FILES['irudia']) && $_FILES['irudia']['error'] === 0) {
        if (!is_dir("irudiak")) {
            mkdir("irudiak", 0777, true);
        }
        $irudiIzena = time() . "_" . basename($_FILES['irudia']['name']);
        $irudiBidea = "irudiak/" . $irudiIzena;
        move_uploaded_file($_FILES['irudia']['tmp_name'], $irudiBidea);
    }

    $sql = "INSERT INTO produktuak (izena, kategoria, mota, modeloa, prezioa, konektibitatea, irudia, egoera, stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssdsssi", $izena, $kategoria, $mota, $modeloa, $prezioa, $konektibitatea, $irudiBidea, $egoera, $stock);
    $stmt->execute();

    header("Location: produktuak.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produktu berria gehitu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "menu.php"; ?>

<div class="wrapper">
    <?php include "sidebar.php"; ?>

    <main class="main-content">
        <section id="formularioa">
           

            <form method="POST" enctype="multipart/form-data">
                <label>Produktuaren Izena</label>
                <input type="text" name="izena" placeholder="Adib: Ordenagailu eramangarria" required>

                <label>Kategoria</label>
                <input type="text" name="kategoria" placeholder="Adib: Hardwarea" required>

                <label>Mota</label>
                <input type="text" name="mota" placeholder="Adib: Portatila" required>

                <label>Modeloa</label>
                <input type="text" name="modeloa" placeholder="Adib: XPS 13" required>

                <label>Prezioa (€)</label>
                <input type="number" step="0.01" name="prezioa" placeholder="0.00" required>

                <label>Konektibitatea</label>
                <input type="text" name="konektibitatea" placeholder="Adib: USB-C, Wi-Fi 6">

                <label>Produktuaren Irudia</label>
                <input type="file" name="irudia" accept="image/*" required>

                <label>Egoera</label>
                <select name="egoera" style="width: 100%; padding: 8px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #aaa;">
                    <option value="Ikusgai">Ikusgai</option>
                    <option value="Ez ikusgai">Ez ikusgai</option>
                </select>

                <label>Stock unitateak</label>
                <input type="number" name="stock" placeholder="Unitate kopurua" required>

                <button type="submit">Gorde Produktua</button>
            </form>
        </section>
    </main>
</div>

<?php include "footer.php"; ?>

</body>
</html>