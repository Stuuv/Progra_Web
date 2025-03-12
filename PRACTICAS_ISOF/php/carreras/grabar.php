<?php
header("Content-Type: application/json");
include_once("../Conexion.php");

// Leer el cuerpo de la petición en formato JSON
$data = json_decode(file_get_contents("php://input"), true);

// Verificar que se hayan recibido los datos necesarios
if (isset($data['id_carrera']) && isset($data['nombre'])) {
    $id_carrera = $data['id_carrera'];
    $nombre = $data['nombre'];

    // Preparar la consulta para insertar los datos y evitar inyección SQL
    $stmt = $conn->prepare("INSERT INTO carreras (id_carrera, nombre) VALUES (?, ?)");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["message" => "Error en la preparación de la consulta: " . $conn->error]);
        exit();
    }

    $stmt->bind_param("ss", $id_carrera, $nombre);

    // Ejecutar la consulta y retornar la respuesta
    if ($stmt->execute()) {
        echo json_encode(["message" => "Carrera agregado correctamente"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error al insertar la carrera: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(400);
    echo json_encode(["message" => "Datos incompletos"]);
}
?>
