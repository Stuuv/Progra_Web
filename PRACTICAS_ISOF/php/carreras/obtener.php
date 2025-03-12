<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include('../Conexion.php'); // Archivo con la conexión a la BD

if (isset($_GET['id_carrera'])) {
    $id_carrera = $_GET['id_carrera'];

    $stmt = $conn->prepare("SELECT id_carrera, nombre FROM carreras WHERE id_carrera = ?");
    $stmt->bind_param("i", $id_carrera);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    echo json_encode($data ?: []); // Si no hay datos, devuelve un array vacío

    $stmt->close();
} else {
    echo json_encode(["error" => "ID de carrera no especificado"]);
}

$conn->close();
?>
