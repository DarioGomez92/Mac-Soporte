<?php
/* Conexion a la base de datos */
$host = 'localhost';        
$usuario = 'root';         
$contrasena = '';         
$basedatos = 'macsoporte_db'; 

$conexion = new mysqli($host, $usuario, $contrasena, $basedatos);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
