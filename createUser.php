<?php
include("konexioa.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $izena = $_POST['izena'];
    $abizena = $_POST['abizena'];
    $dni = $_POST['dni'];
    $email = $_POST['email'];
    $telefonoa = $_POST['telefonoa'];
    $helbidea = $_POST['helbidea'];
    $pasahitza = password_hash($_POST['pasahitza'], PASSWORD_DEFAULT);//Pasahitza enkriptatzeko 
    $rol = "bezeroa";

    // Comprobar email existente
    $check = "SELECT id FROM erabiltzaileak WHERE email='$email'";
    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) > 0) {
        $error = "Email hau jada existitzen da";
    } else {

        $sql = "INSERT INTO erabiltzaileak
        (izena, abizena, dni, email, telefonoa, pasahitza, helbidea, rol)
        VALUES
        ('$izena', '$abizena', '$dni', '$email', '$telefonoa', '$pasahitza', '$helbidea', '$rol')";

        if (mysqli_query($conn, $sql)) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Errorea erabiltzailea sortzean";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="eu">

<head>
    <meta charset="UTF-8">
    <title>Erabiltzailea sortu</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<header>
            <?php include "menu.php"; ?>

</header>

<section id="create-user">
    <h1>ERABILTZAILEA SORTU</h1>

    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <div class="register-box">
        <form method="post">

            <label for="izena"><i class="fa fa-id-card"></i> Izena:</label>
            <input type="text" id="izena" name="izena" required>

            <label for="abizena"><i class="fa fa-id-badge"></i> Abizena:</label>
            <input type="text" id="abizena" name="abizena" required>

            <label for="dni"><i class="fa fa-address-card"></i> DNI:</label>
            <input type="text" id="dni" name="dni" required>

            <label for="helbidea"><i class="fa fa-home"></i> Helbidea:</label>
            <input type="text" id="helbidea" name="helbidea" required>

            <label for="telefonoa"><i class="fa fa-phone"></i> Telefonoa:</label>
            <input type="text" id="telefonoa" name="telefonoa" maxlength="9" required>

            <label for="email"><i class="fa fa-user"></i> Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="pasahitza"><i class="fa fa-key"></i> Pasahitza:</label>
            <input type="password" id="pasahitza" name="pasahitza" required>

            <button type="submit">Sortu</button>
        </form>
    </div>
</section>

<?php include "footer.php"; ?>

</body>
</html>
