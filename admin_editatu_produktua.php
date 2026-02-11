<?php
session_start();
require_once "konexioa.php";

/* Administratzailea dela egiaztatu */
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administratzailea') {
    header("Location: index.php");
    exit;
}

/* Produktua eguneratu */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $sql = "UPDATE produktuak 
            SET izena=?, kategoria=?, mota=?, modeloa=?, prezioa=?, stock=?, egoera=?
            WHERE id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssdisi",
        $_POST['izena'],
        $_POST['kategoria'],
        $_POST['mota'],
        $_POST['modeloa'],
        $_POST['prezioa'],
        $_POST['stock'],
        $_POST['egoera'],
        $_POST['id']
    );
    $stmt->execute();
    // Opcional: añadir una alerta de éxito aquí
}

/* Produktu guztiak lortu */
$result = $conn->query("SELECT * FROM produktuak");
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produktuak Editatu</title>
    <link rel="stylesheet" href="style.css?v=2">

</head>
<body>

    <header>
        <?php include "menu.php"; ?>
    </header>

    <div class="wrapper">
        <?php include "sidebar.php"; ?>

        <main class="main-content">
            <section id="editatu">

                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Izena</th>
                            <th>Kategoria</th>
                            <th>Mota / Modeloa</th>
                            <th>Prezioa (€)</th>
                            <th>Stock</th>
                            <th>Egoera</th>
                            <th>Ekintza</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($p = $result->fetch_assoc()) { ?>
                        <tr>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <td><input type="text" name="izena" value="<?= htmlspecialchars($p['izena']) ?>" required></td>
                                <td><input type="text" name="kategoria" value="<?= htmlspecialchars($p['kategoria']) ?>" required></td>
                                <td>
                                    <input type="text" name="mota" value="<?= htmlspecialchars($p['mota']) ?>" style="margin-bottom:4px;">
                                    <input type="text" name="modeloa" value="<?= htmlspecialchars($p['modeloa']) ?>">
                                </td>
                                <td><input type="number" step="0.01" name="prezioa" value="<?= $p['prezioa'] ?>" required></td>
                                <td><input type="number" name="stock" value="<?= $p['stock'] ?>" required></td>
                                <td>
                                    <select name="egoera">
                                        <option value="Ikusgai" <?= $p['egoera'] == 'Ikusgai' ? 'selected' : '' ?>>Ikusgai</option>
                                        <option value="Ez ikusgai" <?= $p['egoera'] == 'Ez ikusgai' ? 'selected' : '' ?>>Ez ikusgai</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="submit" class="btn-update">Eguneratu</button>
                                </td>
                            </form>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </section>
        </main> 
    </div> 

    <?php include "footer.php"; ?>

</body>
</html>