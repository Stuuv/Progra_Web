<?php
include '../Conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id_carrera']) && !empty($_POST['id_carrera'])) {
        $id_carrera = $conn->real_escape_string($_POST['id_carrera']);

        // Preparar la consulta
        $sql = "DELETE FROM carreras WHERE id_carrera = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("s", $id_carrera);
            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Registro eliminado correctamente"]);
            } else {
                echo json_encode(["success" => false, "message" => "Error al eliminar el registro"]);
            }
            $stmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "Error en la consulta"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "ID no recibido"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
}

$conn->close();
