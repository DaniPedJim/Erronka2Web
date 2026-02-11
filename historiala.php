<?php
include("konexioa.php");
if (session_status() == PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = intval($_SESSION['id']);

// bere erosketa guztiak
$sql = "SELECT * FROM erosketak WHERE erabiltzailea_id = $user_id ORDER BY data DESC";
$result = mysqli_query($conn, $sql);
$compras = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="eu">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Historiala</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="historial.css">



</head>

<body>

<?php include("menu.php"); ?>

<section id="historiala">
<h2>Zure Erosketen Historiala</h2>

<?php if (empty($compras)) { ?>
    <p class="empty-historiala">Ez duzu oraindik erosketarik egin.</p>
<?php } else { ?>
    <?php foreach ($compras as $compra) { 
        $id_erosketa = $compra['id'];
        $sql_det = "SELECT p.izena, p.kategoria, p.mota, p.modeloa, p.konektibitatea, p.irudia,
                           x.kantitatea, x.prezioa_momentuan
                    FROM erosketa_xehetasunak x
                    JOIN produktuak p ON x.produktua_id = p.id
                    WHERE x.erosketa_id = $id_erosketa";
        $res_det = mysqli_query($conn, $sql_det);
        $detalles = mysqli_fetch_all($res_det, MYSQLI_ASSOC);
    ?>
        <div class="compra-card">
            <h3>
                 Erosketa #<?php echo $id_erosketa; ?> |
                <?php echo $compra['data']; ?> |
                Egoera: <?php echo htmlspecialchars($compra['bidalketa_egoera']); ?>
            </h3>
            <?php 
                if (!empty($compra['factura_pdf'])) { 
                    $nombre_archivo = basename($compra['factura_pdf']); 
    $pdf_url = "pdf/" . $nombre_archivo;
            ?>
    <p>
        <a href="<?php echo $pdf_url; ?>" target="_blank" class="download-btn">
            📄 Deskargatu faktura
        </a>
    </p>
<?php } ?>


            <?php foreach ($detalles as $d) { ?>
                <div class="product-card">
                    <?php if (!empty($d['irudia'])) { ?>
                        <img src="<?php echo htmlspecialchars($d['irudia']); ?>" alt="<?php echo htmlspecialchars($d['izena']); ?>">
                    <?php } ?>
                    <div class="product-info">
                        <h4><?php echo htmlspecialchars($d['izena']); ?></h4>
                        <p><strong>Kategoria:</strong> <?php echo htmlspecialchars($d['kategoria']); ?></p>
                        <p><strong>Mota:</strong> <?php echo htmlspecialchars($d['mota']); ?></p>
                        <p><strong>Modeloa:</strong> <?php echo htmlspecialchars($d['modeloa']); ?></p>
                        <p><strong>Konektibitatea:</strong> <?php echo htmlspecialchars($d['konektibitatea']); ?></p>
                        <p><strong>Kantitatea:</strong> <?php echo intval($d['kantitatea']); ?></p>
                        <p><strong>Prezioa:</strong> <?php echo number_format($d['prezioa_momentuan'], 2); ?>€</p>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
<?php } ?>
</section>
    <?php include "footer.php"; ?>

</body>
</html>
