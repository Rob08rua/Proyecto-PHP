<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM cuentas WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Código HTML para mostrar la información del perfil
?>
