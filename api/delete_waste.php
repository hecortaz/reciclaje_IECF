<?php
header('Content-Type: application/json');
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;
    
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
        exit;
    }
    
    $result = executeQuery($conn, "DELETE FROM residuos WHERE id = $id");
    
    if ($conn->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Registro eliminado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el registro']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

$conn->close();
?>
