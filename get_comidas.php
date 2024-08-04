<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "meal_planner";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT * FROM comidas";
$result = $conn->query($sql);

$comidas = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $comidas[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($comidas);
?>