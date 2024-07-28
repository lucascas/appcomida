<?php
include 'conexion.php';

$input = file_get_contents("php://input");
$data = json_decode($input, true);
$comidaId = $data['comidaId'];
$categories = $data['categories'];

if (isset($comidaId) && isset($categories)) {
    $categoryString = implode(',', $categories);
    $query = "UPDATE comidas SET categoria='$categoryString' WHERE id='$comidaId'";

    if (mysqli_query($conn, $query)) {
        echo json_encode(["message" => "Categoría actualizada exitosamente"]);
    } else {
        echo json_encode(["message" => "Error al actualizar la categoría: " . mysqli_error($conn)]);
    }
} else {
    echo json_encode(["message" => "Datos incompletos"]);
}

mysqli_close($conn);
?>
