<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'db.php';  // Incluir la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];

    // Insertar en la base de datos
    $sql = "INSERT INTO productos (nombre, descripcion, precio, cantidad) 
            VALUES (?, ?, ?, ?)";
    
    // Preparar la consulta
    if ($stmt = $conn->prepare($sql)) {
        // Enlazar parámetros (tipo de datos: s = string, d = double, i = integer, b = blob)
        $stmt->bind_param("ssdi", $nombre, $descripcion, $precio, $cantidad);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "Producto registrado exitosamente.";
            header('Location: ver_productos.php');
        } else {
            echo "Error al ejecutar la consulta: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $con->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Producto</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        form { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        input, textarea { width: 100%; padding: 10px; margin: 10px -10px; border-radius: 4px; border: 1px solid #ccc; }
        button { padding: 10px 15px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #45a049; }        
    </style>
</head>
<body>

<h2>Registrar Producto</h2>
<form action="alta.php" method="POST">
    <label for="nombre">Nombre del Producto</label>
    <input type="text" id="nombre" name="nombre" required>

    <label for="descripcion">Descripción</label>
    <textarea id="descripcion" name="descripcion" required></textarea>

    <label for="precio">Precio</label>
    <input type="number" step="0.01" id="precio" name="precio" required>

    <label for="cantidad">Cantidad</label>
    <input type="number" id="cantidad" name="cantidad" required>
    <br><br>
    <button type="submit">Registrar Producto</button>
</form>

<!-- Botón de salida para regresar al login -->
<br>
<a href="ver_productos.php"><button>Regresar</button></a>

</body>
</html>
