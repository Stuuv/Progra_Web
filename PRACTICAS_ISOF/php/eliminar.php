<?php
include 'Conexion.php';

// sql to delete a record
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = $conn->real_escape_string ($_POST ['Matricula']);
    $sql = "DELETE FROM alumnos WHERE matricula=23030897";

    if ($conn->query($sql) === TRUE) { 
    echo "Record deleted successfully";
    } else {
    echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();