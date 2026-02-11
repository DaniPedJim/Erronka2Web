<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$servername = "127.0.0.1";
$erabizena   = "root";
$pasahitza   = "";
$datubase   = "erronka2";
$port       = 3310; 

$conn = new mysqli($servername, $erabizena, $pasahitza, $datubase, $port);

if ($conn->connect_error) {
    die("Errorea konexioan: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

if (!isset($_SESSION['izena'])) {
    $_SESSION['izena'] = '';
}
