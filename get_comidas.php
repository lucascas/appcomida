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

// Consulta para obtener todas las comidas de la tabla weekly_plans
$sql = "SELECT lunes_almuerzo, lunes_cena, martes_almuerzo, martes_cena, miercoles_almuerzo, miercoles_cena, jueves_almuerzo, jueves_cena, viernes_almuerzo, viernes_cena, categoria FROM weekly_plans";
$result = $conn->query($sql);

$comidas = array();

// Recorrer los resultados y agregarlos al array de comidas
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        foreach ($row as $key => $value) {
            if ($key != 'categoria' && !empty($value)) {
                $comidas[] = array("nombre" => $value, "categoria" => $row['categoria']);
            }
        }
    }
} else {
    echo json_encode([]);
    exit();
}

echo json_encode($comidas);

$conn->close();
?>
