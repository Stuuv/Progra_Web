<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'conexion.php';

// Verificar si se recibió la matrícula por GET
$matricula = $_GET['matricula'] ?? '';

if (empty($matricula)) {
    echo json_encode(["error" => "Matrícula no proporcionada"]);
    exit;
}

// Preparar la consulta SQL para eliminar el alumno
$sql = "DELETE FROM alumnos WHERE matricula = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "Error en la consulta: " . $conn->error]);
    exit;
}

$stmt->bind_param("s", $matricula);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => "Alumno eliminado correctamente"]);
} else {
    echo json_encode(["error" => "No se encontró el alumno con esa matrícula"]);
}

$stmt->close();
$conn->close();