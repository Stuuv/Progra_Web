<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'Conexion.php';

function obtenerRegistros($conn) 
{
    $sql = "SELECT * FROM alumnos";
    $result = $conn->query($sql);
    $registros = [];
    if ($result === false) {
        die("Error en la consulta: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $registros[] = $row;
        }
    }
    return $registros;
}

$registros = obtenerRegistros($conn);
$conn->close();

header('Content-Type: application/json');
echo json_encode($registros);
?>
