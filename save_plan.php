<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "meal_planner";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos enviados desde el frontend
$data = json_decode(file_get_contents('php://input'), true);

// Preparar la consulta SQL
$sql = "INSERT INTO weekly_plans (lunes_almuerzo, lunes_cena, martes_almuerzo, martes_cena, miercoles_almuerzo, miercoles_cena, jueves_almuerzo, jueves_cena, viernes_almuerzo, viernes_cena, comprar_super, fecha_creación)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssssss", $data['lunes_almuerzo'], $data['lunes_cena'], $data['martes_almuerzo'], $data['martes_cena'], $data['miercoles_almuerzo'], $data['miercoles_cena'], $data['jueves_almuerzo'], $data['jueves_cena'], $data['viernes_almuerzo'], $data['viernes_cena'], $data['comprar_super'], $data['fecha_creación']);

// Ejecutar la consulta
if ($stmt->execute()) {
    echo json_encode(["message" => "Plan saved successfully"]);
} else {
    echo json_encode(["message" => "Error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
