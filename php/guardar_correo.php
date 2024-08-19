<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/../config/config.php';

header('Content-Type: application/json');

$start_time = microtime(true);

$bot_token = $_ENV['TELEGRAM_BOT_TOKEN'];
$chat_id = $_ENV['TELEGRAM_CHAT_ID'];

if (!$bot_token || !$chat_id) {
    error_log("Falta el token del bot o el ID del chat. Token del bot: $bot_token, ID del chat: $chat_id");
    die("Error: Falta el token del bot o el ID del chat.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];

    if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {

        $stmt = $conn->prepare("INSERT INTO correos_registrados (correo) VALUES (?)");
        if ($stmt === false) {
            echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta INSERT']);
            exit;
        }

        $stmt->bind_param("s", $correo);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Correo registrado con éxito']);
            ignore_user_abort(true);
            ob_start();

            $message = "Nuevo registro: $correo";
            $url = "https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$chat_id&text=" . urlencode($message);
            $response = file_get_contents($url);

            ob_end_flush();
            flush();

            if ($response === false) {
                error_log("Error al enviar el mensaje a Telegram.");
            }

        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar el correo']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Correo inválido']);
    }
}
$end_time = microtime(true);
$execution_time = $end_time - $start_time;
error_log("Tiempo de ejecución: " . $execution_time . " segundos");

$conn->close();
?>
