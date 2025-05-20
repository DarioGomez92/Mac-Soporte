<?php
$host = 'localhost';        
$usuario = 'root';         
$contrasena = '';         
$basedatos = 'macsoporte_db'; 

$conn = new mysqli($host, $usuario, $contrasena, $basedatos);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
