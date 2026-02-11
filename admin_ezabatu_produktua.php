<?php
session_start();
require_once "konexioa.php";

/* Administratzailea dela egiaztatu */
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administratzailea') {
    header("Location: index.php");
    exit;
}

/* Produktua ezabatu */
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM produktuak WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    // Redirigir para limpiar la URL tras borrar
    header("Location: admin_ezabatu_produktua.php"); 
    exit;
}

/* Produktu guztiak lortu */
$result = $conn->query("SELECT * FROM produktuak");
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sarrera - Ezabatu</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="admin_ezabatu_produktua.css?v=<?php echo time(); ?>">
</head>
<body>

    <header>
        <?php include "menu.php"; ?>
    </header>

    <div class="wrapper">
        <?php include "sidebar.php"; ?>

        <main class="main-content">
            <section class="informazioa">
                <h2>🗑️ Produktuak ezabatu</h2>
                
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Izena</th>
                            <th>Kategoria</th>
                            <th>Prezioa</th>
                            <th>Ekintza</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?= htmlspecialchars($row['izena']) ?></td>
                                <td><?= htmlspecialchars($row['kategoria']) ?></td>
                                <td><?= number_format($row['prezioa'], 2) ?> €</td>
                                <td>
                                    <a href="?id=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Ziur zaude?')">
                                        Ezabatu
                                    </a>
                                </td>
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