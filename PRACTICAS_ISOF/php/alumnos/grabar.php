<?php
header("Content-Type: application/json");
include_once("../Conexion.php");

// Leer el cuerpo de la petición en formato JSON
$data = json_decode(file_get_contents("php://input"), true);

// Verificar que se hayan recibido los datos necesarios
if (isset($data['matricula']) && isset($data['nombre']) && isset($data['direccion'])) {
    $matricula = $data['matricula'];
    $nombre    = $data['nombre'];
    $direccion = $data['direccion'];

    // Preparar la consulta para insertar los datos y evitar inyección SQL
    $stmt = $conn->prepare("INSERT INTO alumnos (matricula, nombre, direccion) VALUES (?, ?, ?)");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["message" => "Error en la preparación de la consulta: " . $conn->error]);
        exit();
    }

    $stmt->bind_param("sss", $matricula, $nombre, $direccion);

    // Ejecutar la consulta y retornar la respuesta
    if ($stmt->execute()) {
        echo json_encode(["message" => "Alumno agregado correctamente"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error al insertar el alumno: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(400);
    echo json_encode(["message" => "Datos incompletos"]);
}
?>
