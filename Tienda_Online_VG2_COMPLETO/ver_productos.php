<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

$sql = "SELECT * FROM productos";
$stmt = $conn->query($sql);

// Verificar si la consulta fue exitosa
if ($stmt) {
    $productos = [];
    while ($row = $stmt->fetch_assoc()) {
        $productos[] = $row;  // Guardar cada fila en el array $productos
    }
} else {
    echo "Error en la consulta: " . $con->error;  // Mostrar error si la consulta falla
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        table { width: 100%; margin: 20px 0; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ccc; }
        button { padding: 5px 10px; background-color: #f44336; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #d32f2f; }
        .btn-container { margin-bottom: 20px; }
    </style>
</head>
<body>

<h2>Inventario de Productos</h2>

<!-- Botón para dar de alta productos -->
<div class="btn-container">
    <a href="alta.php"><button>Dar de Alta Producto</button></a>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($productos as $producto): ?>
            <tr>
                <td><?= $producto['id'] ?></td>
                <td><?= $producto['nombre'] ?></td>
                <td><?= $producto['descripcion'] ?></td>
                <td><?= $producto['precio'] ?></td>
                <td><?= $producto['cantidad'] ?></td>
                <td>
                    <a href="editar.php?id=<?= $producto['id'] ?>"><button>Editar</button></a>
                    <a href="eliminar.php?id=<?= $producto['id'] ?>"><button>Eliminar</button></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Botón de salida para regresar al login -->
<a href="productos.php"><button>Salir</button></a>

</body>
</html>
