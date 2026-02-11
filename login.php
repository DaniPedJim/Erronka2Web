<?php
session_start();
include("konexioa.php");


if (!isset($_SESSION['saskia']) && isset($_COOKIE['saskia'])) {
    $_SESSION['saskia'] = json_decode($_COOKIE['saskia'], true);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['erabiltzailea'];
    $pasahitza = $_POST['pasahitza'];

    $sql = "SELECT * FROM erabiltzaileak WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($pasahitza, $row['pasahitza'])) {

            // sesioko datuak gordetzeko
            $_SESSION['id'] = $row['id'];
            $_SESSION['izena'] = $row['izena'];
            $_SESSION['rol'] = $row['rol'];

            // indexera eramateko 
            header("Location: index.php");
            exit();

        } else {
            $error = "Pasahitza okerra";
        }

    } else {
        $error = "Erabiltzailea ez da existitzen";
    }
}
?>
<!DOCTYPE html>
<html lang="eu">

<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<header>
          <?php include "menu.php"; ?>

</header>

<section id="login">
    <h1>LOGIN</h1>

    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <div class="login-box">
        <form method="post">

            <label for="erabiltzailea">
                <i class="fa fa-user"></i> Erabiltzailea:
            </label>
            <input type="email" id="erabiltzailea" name="erabiltzailea" placeholder="Zure email" required>

            <label for="pasahitza">
                <i class="fa fa-key"></i> Pasahitza:
            </label>
            <input type="password" id="pasahitza" name="pasahitza" placeholder="Idatzi hemen" required>

            <button type="submit">Sartu</button>
        </form>

        <div class="no-account">
            <a href="createUser.php">
                Ez duzu konturik?<br><strong>Sartu hemen</strong>
            </a>
        </div>
    </div>
</section>

<?php include "footer.php"; ?>

</body>
</html>
