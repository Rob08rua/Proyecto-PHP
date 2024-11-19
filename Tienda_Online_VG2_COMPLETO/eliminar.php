<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Preparar consulta SQL para eliminar producto
    $sql = "DELETE FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);    

    if ($stmt === false) {
        // Mostrar el error de la consulta SQL usando mysqli_error()
        die('Error al preparar la consulta SQL: ' . mysqli_error($con));
    }

    // Ejecutar la consulta
    $stmt->bind_param('i', $id); // 'i' para integer
    if ($stmt->execute()) {
        //echo "Producto eliminado exitosamente.";
        echo "<script>alert('Producto eliminado exitosamente');</script>";
    } else {
        //echo "Hubo un error al eliminar el producto.";
        echo "<script>alert('Hubo un error al eliminar el producto');</script>";
    }
} else {
    //echo "ID no válido o no especificado.";
    echo "<script>alert('ID no válido o no especificado');</script>";
}
header('Location: ver_productos.php');
?>

