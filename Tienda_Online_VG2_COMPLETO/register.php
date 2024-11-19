<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    $sql = "INSERT INTO cuentas (nombre, email, password, telefono, direccion) VALUES ('$nombre', '$email', '$password', '$telefono', '$direccion')";

    if ($conn->query($sql) === TRUE) {
        echo "Registro exitoso. <a href='login.html'>Iniciar Sesión</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
