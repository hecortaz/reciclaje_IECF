<?php
header('Content-Type: application/json');
include '../config/database.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
    exit;
}

$result = executeQuery($conn, "SELECT * FROM residuos WHERE id = $id");

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    echo json_encode(['success' => true, 'data' => $data]);
} else {
    echo json_encode(['success' => false, 'message' => 'Registro no encontrado']);
}

$conn->close();
?>
