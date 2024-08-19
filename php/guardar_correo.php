<?php
include __DIR__ . '/../config/config.php';  

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];

    if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("INSERT INTO correos_registrados (correo) VALUES (?)");
        if ($stmt) {
            $stmt->bind_param("s", $correo);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Correo registrado con éxito']);
            } else {
                echo json_encode(['success' => false, 'message' => 'El correo ya está registrado o ha ocurrido un error']);
            }

            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Error en la preparación de la declaración.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Correo inválido']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

$conn->close();
?>
