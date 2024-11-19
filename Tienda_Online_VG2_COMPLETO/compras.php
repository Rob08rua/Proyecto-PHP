<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Por favor, inicia sesión para continuar.");
}

$productos = $_POST['productos']; // Array con los IDs de los productos y sus cantidades
$total = 0;

foreach ($productos as $producto_id => $cantidad) {
    $sql = "SELECT nombre, precio, stock FROM productos WHERE id = $producto_id";
    $result = $conn->query($sql);
    $producto = $result->fetch_assoc();

    if ($producto['stock'] < $cantidad) {
        die("No hay suficiente stock para el producto: " . $producto['nombre']);
    }

    $total += $producto['precio'] * $cantidad;
}

$sql = "INSERT INTO compras (user_id, total) VALUES ('{$_SESSION['user_id']}', '$total')";
if ($conn->query($sql) === TRUE) {
    $compra_id = $conn->insert_id;
    foreach ($productos as $producto_id => $cantidad) {
        $sql = "INSERT INTO transacciones (compra_id, producto_id, cantidad, precio)
                VALUES ('$compra_id', '$producto_id', '$cantidad', 
                        (SELECT precio FROM productos WHERE id = $producto_id))";
        $conn->query($sql);

        $sql = "UPDATE productos SET stock = stock - $cantidad WHERE id = $producto_id";
        $conn->query($sql);
    }
    echo "Compra realizada con éxito.";
} else {
    echo "Error al realizar la compra: " . $conn->error;
}

$conn->close();
?>
