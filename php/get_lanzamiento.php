<?php
include __DIR__ . '/../config/config.php';

header('Content-Type: application/json');

$sql = "SELECT fecha_lanzamiento FROM lanzamiento";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['fecha_lanzamiento' => $row['fecha_lanzamiento']]);
} else {
    echo json_encode(['error' => 'Fecha de lanzamiento no encontrada']);
}

$conn->close();
?>
