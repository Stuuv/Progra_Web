<?php
include '../Conexion.php';

//Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //lIMPIAR Y VALIDAR DATOS 
    $matricula = $conn ->real_escape_string ($_POST["matricula"]);
    $nombre = $conn ->real_escape_string ($_POST["nombre"]);
    $direccion = $conn ->real_escape_string ($_POST["direccion"]);


    //insertar datos
    $sql =  "INSERT INTO alumnos (matricula, nombre, direccion)
             VALUES ('$matricula', '$nombre', '$direccion')";
    
    if($conn->query($sql) == TRUE){
        echo 'registro existoso';
    } else {
        echo "Error" , $sql , "<br>" , $conn->error;
    }
}
$conn->close();