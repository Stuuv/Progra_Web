<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'conexion.php';

// Obtener la matrícula de la petición GET
$matricula = $_GET['matricula'] ?? '';

if (empty($matricula)) {
    echo json_encode(["error" => "Matrícula no proporcionada"]);
    exit;
}

//var_dump($matricula);

// Preparar la consulta SQL para obtener el alumno
$sql = "SELECT matricula, nombre, direccion FROM alumnos WHERE matricula = ?";

// Usar una declaración preparada
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die(json_encode(["error" => "Error en la consulta: " . $conn->error]));
}

$stmt->bind_param("s", $matricula);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header('Content-Type: application/json');
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(["error" => "Alumno no encontrado"]);
}

$stmt->close();
$conn->close();
?>