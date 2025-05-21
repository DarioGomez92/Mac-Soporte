<?php
session_start();

header('Content-Type: application/json');

if (isset($_SESSION['usuario_id'])) {
    echo json_encode([
        'loggedIn' => true,
        'nombre' => $_SESSION['nombre_completo'],
        'rol' => $_SESSION['rol'] ?? 'cliente'
    ]);
} else {
    echo json_encode(['loggedIn' => false]);
}
