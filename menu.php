<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("konexioa.php");

$kantitatea = 0;

if (isset($_SESSION['id'])) {
    $erabiltzailea_id = $_SESSION['id'];

    $res = mysqli_query($conn, "SELECT SUM(kantitatea) AS total 
        FROM saskia 
        WHERE erabiltzailea_id=$erabiltzailea_id");

    $fila = mysqli_fetch_assoc($res);
    $kantitatea = $fila['total'] ?? 0;
}



// saskiko produktu kantitatea
$total_items = isset($_SESSION['saskia']) ? count($_SESSION['saskia']) : 0;
?>

<!-- MENUA -->
<header>

    <!-- MENÚ PC -->
    <nav class="menu-pc">
        <a class="berritech-btn">
            <img src="irudiak/LogoErronka1.png" alt="Berritech">
        </a>
        <a href="index.php">SARRERA</a>
        <a href="produktuak.php">PRODUKTUAK</a>
        <a href="norgara.php">NOR GARA</a>
        <a href="formularioa.php">FORMULARIOA</a>

        <?php if (!isset($_SESSION['id'])) { ?>
            <a href="login.php">SAIOA HASI</a>
        <?php } else { ?>
            <div class="user-menu">
            <a href="saskia.php" class="user-name-movil carrito-icon">
    Kaixo, <?php echo htmlspecialchars($_SESSION['izena']); ?> 🛒 
    <span class="contador"><?php echo $kantitatea; ?></span>
</a>


                <div class="user-dropdown">
                    <a href="historiala.php">Historiala</a>
                    <a href="logout.php">Saioa itxi</a>
                </div>
            </div>
        <?php } ?>
    </nav>

    <!-- MUGIKORRERAKO -->
    <nav class="menu-movil">
        <div class="top-bar">
            <img src="irudiak/LogoErronka1.png" class="logo-movil" alt="Berritech">
            <button id="btn-menu">☰</button>
        </div>

        <div class="desplegable" id="menu-desplegable">
            <a href="index.php">SARRERA</a>
            <a href="produktuak.php">PRODUKTUAK</a>
            <a href="norgara.php">NOR GARA</a>
            <a href="formularioa.php">FORMULARIOA</a>

            <?php if (!isset($_SESSION['id'])) { ?>
                <a href="login.php">LOGIN</a>
            <?php } else { ?>
<a href="saskia.php" class="user-name-movil carrito-icon">
    Kaixo, <?php echo htmlspecialchars($_SESSION['izena']); ?> 🛒 
    <span class="contador"><?php echo $kantitatea; ?></span>
</a>


                <a href="historiala.php">Historiala</a>
                <a href="logout.php">Saioa itxi</a>
            <?php } ?>
        </div>
    </nav>

</header>

<style>
.user-menu {
    position: relative;
    display: inline-block;
}

.user-dropdown {
    display: none;
    position: absolute;
    background-color: #fff;
    min-width: 120px;
    box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
    z-index: 1000;
}

.user-dropdown a {
    display: block;
    padding: 8px 12px;
    text-decoration: none;
    color: #333;
}

.user-dropdown a:hover {
    background-color: #f1f1f1;
}

.user-menu:hover .user-dropdown {
    display: block;
}

.user-name-movil {
    font-weight: bold;
    display: block;
    padding: 5px 0;
}
.fly-img {
    z-index: 9999;
    pointer-events: none;
    border-radius: 8px;
}

</style>
