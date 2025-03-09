<?php
$servername = "localhost";// puerto :80
$username = "root";
$password = "";
$database = "dbpractica";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>