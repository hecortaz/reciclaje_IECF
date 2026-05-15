<?php
header('Content-Type: application/json');
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = sanitize($_POST['fecha'] ?? '', $conn);
    $grado = sanitize($_POST['grado'] ?? '', $conn);
    $salon = sanitize($_POST['salon'] ?? '', $conn);
    $tipo_residuo = sanitize($_POST['tipo_residuo'] ?? '', $conn);
    $peso_kg = floatval($_POST['peso_kg'] ?? 0);
    $cantidad = intval($_POST['cantidad'] ?? 0);
    $numero_estudiantes = intval($_POST['numero_estudiantes'] ?? 0);
    $estado_residuo = sanitize($_POST['estado_residuo'] ?? '', $conn);
    $clasificacion = sanitize($_POST['clasificacion'] ?? '', $conn);
    
    if (!$fecha || !$grado || !$salon || !$tipo_residuo || $peso_kg <= 0) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit;
    }
    
    $query = "INSERT INTO residuos 
              (fecha, grado, salon, tipo_residuo, peso_kg, cantidad, numero_estudiantes, estado_residuo, clasificacion)
              VALUES ('$fecha', '$grado', '$salon', '$tipo_residuo', $peso_kg, $cantidad, $numero_estudiantes, '$estado_residuo', '$clasificacion')";
    
    if (executeQuery($conn, $query)) {
        echo json_encode(['success' => true, 'message' => 'Registro guardado correctamente', 'id' => $conn->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al guardar el registro']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

$conn->close();
?>
