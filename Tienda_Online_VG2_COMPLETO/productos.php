<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Sanitizar las variables para prevenir inyecciones y XSS
    $username = htmlspecialchars($username);
    $password = htmlspecialchars($password);

    $sql = "SELECT * FROM cuentas WHERE nombre = ?";
    $stmt = $conn->prepare($sql);

    // Verificar si la preparación de la consulta fue exitosa
    if ($stmt === false) {
        // Mostrar el error de la consulta
        echo "Error al preparar la consulta: " . $con->error;
        exit();
    }  

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // Verificar si es el usuario
        if ($admin['nombre'] === 'administrador') {
        // Verificar si la contraseña ingresada coincide con el hash almacenado
            if (password_verify($password, $admin['password'])) {
            // Iniciar la sesión y redirigir
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['username'] = $admin['username'];
                header('Location: ver_productos.php');
                exit();
            } else {
                echo "<script>alert('contraseña incorrectos');</script>";
            }
        } else {
            echo "<script>alert('Usuario incorrecto');</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel ="preload" href = "CssProyecto/style.css" as="style">
    <link href="CssProyecto/style.css" rel="stylesheet">
</head>
<body class="productos-page">
    <div class="login-box">
        <h2 class="productos-h2">Iniciar Sesión</h2>
        <form method="POST">
            <input type="text" id="username" name="username" class="productos-input" placeholder="Usuario" required>
            <input type="password" id="password" name="password" class="productos-input" placeholder="Contraseña" required>
            <button type="submit" class="productos-boton">Iniciar sesión</button>
        </form>
        <div class="link">
            <p><a href="#">¿Olvidaste la contraseña?</a></p>
        </div>
    </div>
</body>
</html>
    <!--Ingresar-->
<?php if (isset($error)) {
    echo "<p style='color: red;'>" . htmlspecialchars($error) . "</p>";
}
?>
