<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);  // Usar la conexión MySQLi
    $stmt->bind_param("i", $id);  // Bind para el parámetro id
    $stmt->execute();
    $result = $stmt->get_result();  // Obtener el resultado de la consulta
    $producto = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];

        $sql = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, cantidad = ? WHERE id = ?";
        $stmt = $con->prepare($sql);  // Usar la conexión MySQLi
        $stmt->bind_param("ssdii", $nombre, $descripcion, $precio, $cantidad, $id);  // Bind para los parámetros
        $stmt->execute();  // Ejecutar la consulta

        echo "Producto actualizado exitosamente.";
    }
} else {
    echo "ID no especificado";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        form { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        input, textarea { width: 100%; padding: 10px; margin: 10px -10px; border-radius: 4px; border: 1px solid #ccc; }
        button { padding: 10px 15px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #45a049; }
    </style>
</head>
<body>

<h2>Editar Producto</h2>
<form action="editar.php?id=<?= $producto['id'] ?>" method="POST">
    <label for="nombre">Nombre del Producto</label>
    <input type="text" id="nombre" name="nombre" value="<?= $producto['nombre'] ?>" required>

    <label for="descripcion">Descripción</label>
    <textarea id="descripcion" name="descripcion" required><?= $producto['descripcion'] ?></textarea>

    <label for="precio">Precio</label>
    <input type="number" step="0.01" id="precio" name="precio" value="<?= $producto['precio'] ?>" required>

    <label for="cantidad">Cantidad</label>
    <input type="number" id="cantidad" name="cantidad" value="<?= $producto['cantidad'] ?>" required>

    <button type="submit">Actualizar Producto</button>
</form>

<!-- Botón de salida para regresar al login -->
<br>
<a href="ver_productos.php"><button>Regresar</button></a>

</body>
</html>
