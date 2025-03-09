<?php

include 'Conexion.php';

// Obtener los datos enviados por POST
$nombre = $_POST['Nombre'];
$direccion = $_POST['Direccion'];
$matricula = $_POST['Matricula'];

// Preparar la consulta
$stmt = $conn->prepare("UPDATE alumnos SET Nombre=?, Direccion=? WHERE Matricula=?");
$stmt->bind_param("sss", $nombre, $direccion, $matricula);

// Ejecutar la consulta
if ($stmt->execute()) {
  echo "alumno actualizdo";
} else {
  echo "Error updating record: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
