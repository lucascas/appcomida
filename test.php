<?php
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$data = json_decode(file_get_contents('php://input'), true);

if ($data === null) {
    echo json_encode(["error" => "No se recibieron datos o el JSON está malformado."]);
} else {
    echo json_encode(["success" => "Datos recibidos correctamente", "data" => $data]);
}
?>
