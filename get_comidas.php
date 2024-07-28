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

// Consulta para obtener todas las comidas de la tabla comidas
$sql = "SELECT id, nombre, categoria, ingredientes FROM comidas";
$result = $conn->query($sql);

$comidas = array();

// Recorrer los resultados y agregarlos al array de comidas
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $comidas[] = $row;
    }
} else {
    echo json_encode([]);
    exit();
}

echo json_encode($comidas);

$conn->close();
?>
