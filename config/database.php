<?php
// Configuración de conexión a la base de datos

$db_host = 'localhost';
$db_user = 'root';
$db_password = ''; // Cambiar según tu configuración
$db_name = 'waste_management';

// Crear conexión
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Verificar conexión
if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}

// Establecer charset a UTF-8
$conn->set_charset('utf8mb4');

// Función para prevenir inyección SQL
function sanitize($data, $conn) {
    return $conn->real_escape_string(htmlspecialchars(trim($data)));
}

// Función para ejecutar consultas con validación
function executeQuery($conn, $query) {
    $result = $conn->query($query);
    if (!$result) {
        die('Error en la consulta: ' . $conn->error);
    }
    return $result;
}
?>
