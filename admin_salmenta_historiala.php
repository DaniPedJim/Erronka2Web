<?php
session_start();
require_once "konexioa.php";

/* Administratzailea dela egiaztatu */
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administratzailea') {
    header("Location: index.php");
    exit;
}

/* Gehien erositako produktuak lortu */
$sql = "
    SELECT 
        p.id,
        p.izena,
        p.irudia,
        SUM(ex.kantitatea) AS guztira_erosita,
        SUM(ex.kantitatea * ex.prezioa_momentuan) AS guztira_dirua
    FROM erosketa_xehetasunak ex
    INNER JOIN produktuak p ON ex.produktua_id = p.id
    GROUP BY p.id, p.izena, p.irudia
    ORDER BY guztira_erosita DESC
";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="eu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salmenta Estatistikak</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="admin_salmentak.css?v=<?php echo time(); ?>">
</head>

<body>

    <header>
        <?php include "menu.php"; ?>
    </header>

    <div class="wrapper">
        <?php include "sidebar.php"; ?>

        <main class="main-content">
            <div class="stats-header">
                <h2>📈 Gehien erositako produktuak</h2>
                <p>Produktu bakoitzaren salmenta kopurua eta diru-sarrerak.</p>
            </div>

            <table class="stats-table">
                <thead>
                    <tr>
                        <th>Irudia</th>
                        <th>Produktua</th>
                        <th>Unitateak salduta</th>
                        <th>Diru guztira (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td class="product-img-td">
                                <?php if ($row['irudia']) { ?>
                                    <img src="<?= htmlspecialchars($row['irudia']) ?>" width="60" height="60">
                                <?php } else { ?>
                                    <span style="color:#999; font-size: 0.8rem;">Irudirik ez</span>
                                <?php } ?>
                            </td>
                            <td><strong><?= htmlspecialchars($row['izena']) ?></strong></td>
                            <td><span class="unit-badge"><?= $row['guztira_erosita'] ?></span></td>
                            <td class="money-text"><?= number_format($row['guztira_dirua'], 2) ?> €</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </main>
    </div>

    <?php include "footer.php"; ?>

</body>
</html>