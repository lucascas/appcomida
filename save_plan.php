<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "meal_planner";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener datos del POST
$data = json_decode(file_get_contents('php://input'), true);

$lunes_almuerzo = $data['lunes_almuerzo'];
$lunes_cena = $data['lunes_cena'];
$martes_almuerzo = $data['martes_almuerzo'];
$martes_cena = $data['martes_cena'];
$miercoles_almuerzo = $data['miercoles_almuerzo'];
$miercoles_cena = $data['miercoles_cena'];
$jueves_almuerzo = $data['jueves_almuerzo'];
$jueves_cena = $data['jueves_cena'];
$viernes_almuerzo = $data['viernes_almuerzo'];
$viernes_cena = $data['viernes_cena'];
$comprar_super = $data['comprar_super'];

// Insertar en la tabla weekly_plans
$sql = "INSERT INTO weekly_plans (lunes_almuerzo, lunes_cena, martes_almuerzo, martes_cena, miercoles_almuerzo, miercoles_cena, jueves_almuerzo, jueves_cena, viernes_almuerzo, viernes_cena, comprar_super, created_at) 
VALUES ('$lunes_almuerzo', '$lunes_cena', '$martes_almuerzo', '$martes_cena', '$miercoles_almuerzo', '$miercoles_cena', '$jueves_almuerzo', '$jueves_cena', '$viernes_almuerzo', '$viernes_cena', '$comprar_super', NOW())";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["message" => "New record created successfully"]);
} else {
    echo json_encode(["error" => "Error: " . $sql . "<br>" . $conn->error]);
}

$conn->close();
?>
