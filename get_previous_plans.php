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

// Consulta para obtener los planes anteriores
$sql = "SELECT lunes_almuerzo, lunes_cena, martes_almuerzo, martes_cena, miercoles_almuerzo, miercoles_cena, jueves_almuerzo, jueves_cena, viernes_almuerzo, viernes_cena, comprar_super, fecha_creación, categoria FROM weekly_plans";
$result = $conn->query($sql);

$planes = array();

// Recorrer los resultados y agregarlos al array de planes
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $planes[] = $row;
    }
} else {
    echo json_encode([]);
    exit();
}

echo json_encode($planes);

$conn->close();
?>
