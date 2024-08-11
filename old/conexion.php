<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "meal_planner";
/*

$servername = "sql105.infinityfree.com";
$username = "if0_36943803";
$password = "9HiF8jHuRJqHZsj";
$dbname = "if0_36943803_comidassemanales";
*/
// Crear la conexión
$conn = new mysqli("sql105.infinityfree.com", "if0_36943803", "9HiF8jHuRJqHZsj", "if0_36943803_comidassemanales");

// Verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
