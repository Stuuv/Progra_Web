<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");

include('../Conexion.php'); // Archivo con la conexión a la BD

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (
        isset($data['id_carrera']) &&
        isset($data['nombre'])
    ) {
        try {
            $stmt = $conn->prepare("UPDATE carreras SET nombre = ? WHERE id_carrera = ?");
            
            $stmt->bind_param("si", 
                $data['nombre'],
                $data['id_carrera']
            );
            
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode(array("mensaje" => "Carrera actualizada correctamente"));
                } else {
                    echo json_encode(array("error" => "No se realizaron cambios"));
                }
            } else {
                echo json_encode(array("error" => "Error al actualizar"));
            }
            
            $stmt->close();
        } catch(Exception $e) {
            echo json_encode(array("error" => "Error: " . $e->getMessage()));
        }
    } else {
        echo json_encode(array("error" => "Datos incompletos"));
    }
} else {
    echo json_encode(array("error" => "Método no permitido"));
}

$conn->close();