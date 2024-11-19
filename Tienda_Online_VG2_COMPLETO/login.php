<?php
session_start();
include 'db.php';

// Decodificar los datos JSON recibidos
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['email']) && isset($data['password'])) {
    $email = $data['email'];
    $password = $data['password'];

    $sql = "SELECT * FROM cuentas WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['nombre'] = $row['nombre'];
            echo json_encode([
                "success" => true,
                "username" => $row['nombre']
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Contraseña incorrecta."
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "No se encontró una cuenta con ese correo electrónico."
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "No se recibieron los datos necesarios."
    ]);
}

$conn->close();
?>
