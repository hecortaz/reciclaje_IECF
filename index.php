<?php
include 'config/database.php';
include 'includes/header.php';

$form_id = isset($_GET['id']) ? sanitize($_GET['id'], $conn) : null;
$edit_mode = false;
$waste_data = null;

if ($form_id) {
    $result = executeQuery($conn, "SELECT * FROM residuos WHERE id = $form_id");
    if ($result->num_rows > 0) {
        $edit_mode = true;
        $waste_data = $result->fetch_assoc();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = sanitize($_POST['fecha'], $conn);
    $grado = sanitize($_POST['grado'], $conn);
    $salon = sanitize($_POST['salon'], $conn);
    $tipo_residuo = sanitize($_POST['tipo_residuo'], $conn);
    $peso_kg = floatval($_POST['peso_kg']);
    $cantidad = intval($_POST['cantidad']);
    $numero_estudiantes = intval($_POST['numero_estudiantes']);
    $estado_residuo = sanitize($_POST['estado_residuo'], $conn);
    $clasificacion = sanitize($_POST['clasificacion'], $conn);
    $form_id = isset($_POST['form-id']) && $_POST['form-id'] ? intval($_POST['form-id']) : null;
    
    if ($form_id && $edit_mode) {
        $query = "UPDATE residuos SET 
                  fecha='$fecha', grado='$grado', salon='$salon', 
                  tipo_residuo='$tipo_residuo', peso_kg=$peso_kg, 
                  cantidad=$cantidad, numero_estudiantes=$numero_estudiantes, 
                  estado_residuo='$estado_residuo', clasificacion='$clasificacion'
                  WHERE id=$form_id";
    } else {
        $query = "INSERT INTO residuos 
                  (fecha, grado, salon, tipo_residuo, peso_kg, cantidad, numero_estudiantes, estado_residuo, clasificacion)
                  VALUES ('$fecha', '$grado', '$salon', '$tipo_residuo', $peso_kg, $cantidad, $numero_estudiantes, '$estado_residuo', '$clasificacion')";
    }
    
    if (executeQuery($conn, $query)) {
        echo '<div class="alert alert-success">✓ Registro ' . ($edit_mode ? 'actualizado' : 'guardado') . ' correctamente</div>';
        $edit_mode = false;
        $waste_data = null;
    } else {
        echo '<div class="alert alert-danger">✗ Error al guardar el registro</div>';
    }
}
?>

<div class="page-header">
    <h1>📝 Registro de Residuos</h1>
    <p>Complete el formulario para registrar un nuevo ingreso de residuos</p>
</div>

<div class="form-container">
    <form id="waste-form" method="POST">
        <input type="hidden" id="form-id" name="form-id" value="<?php echo $waste_data['id'] ?? ''; ?>">
        
        <div class="form-row">
            <div class="form-group">
                <label for="fecha">Fecha *</label>
                <input type="date" id="fecha" name="fecha" required value="<?php echo $waste_data['fecha'] ?? date('Y-m-d'); ?>">
            </div>
            <div class="form-group">
                <label for="grado">Grado *</label>
                <select id="grado" name="grado" required>
                    <option value="">Seleccione un grado</option>
                    <option value="6°" <?php echo ($waste_data['grado'] ?? '') === '6°' ? 'selected' : ''; ?>>6°</option>
                    <option value="7°" <?php echo ($waste_data['grado'] ?? '') === '7°' ? 'selected' : ''; ?>>7°</option>
                    <option value="8°" <?php echo ($waste_data['grado'] ?? '') === '8°' ? 'selected' : ''; ?>>8°</option>
                    <option value="9°" <?php echo ($waste_data['grado'] ?? '') === '9°' ? 'selected' : ''; ?>>9°</option>
                    <option value="10°" <?php echo ($waste_data['grado'] ?? '') === '10°' ? 'selected' : ''; ?>>10°</option>
                    <option value="11°" <?php echo ($waste_data['grado'] ?? '') === '11°' ? 'selected' : ''; ?>>11°</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="salon">Salón *</label>
                <input type="text" id="salon" name="salon" placeholder="Ej: Salón 8-A" required value="<?php echo $waste_data['salon'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label for="tipo_residuo">Tipo de Residuo *</label>
                <select id="tipo_residuo" name="tipo_residuo" required onchange="classifyWaste()">
                    <option value="">Seleccione un tipo</option>
                    <option value="Papel" <?php echo ($waste_data['tipo_residuo'] ?? '') === 'Papel' ? 'selected' : ''; ?>>Papel</option>
                    <option value="PET" <?php echo ($waste_data['tipo_residuo'] ?? '') === 'PET' ? 'selected' : ''; ?>>PET</option>
                    <option value="Orgánico" <?php echo ($waste_data['tipo_residuo'] ?? '') === 'Orgánico' ? 'selected' : ''; ?>>Orgánico</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="peso_kg">Peso (kg) *</label>
                <input type="number" id="peso_kg" name="peso_kg" step="0.01" min="0" required value="<?php echo $waste_data['peso_kg'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label for="cantidad">Cantidad *</label>
                <input type="number" id="cantidad" name="cantidad" min="0" required value="<?php echo $waste_data['cantidad'] ?? ''; ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="numero_estudiantes">Número de Estudiantes *</label>
                <input type="number" id="numero_estudiantes" name="numero_estudiantes" min="0" required value="<?php echo $waste_data['numero_estudiantes'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label for="estado_residuo">Estado del Residuo *</label>
                <select id="estado_residuo" name="estado_residuo" required onchange="classifyWaste()">
                    <option value="">Seleccione un estado</option>
                    <option value="Aprovechable" <?php echo ($waste_data['estado_residuo'] ?? '') === 'Aprovechable' ? 'selected' : ''; ?>>Aprovechable</option>
                    <option value="No aprovechable" <?php echo ($waste_data['estado_residuo'] ?? '') === 'No aprovechable' ? 'selected' : ''; ?>>No aprovechable</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="clasificacion">Clasificación (Automática)</label>
            <input type="text" id="clasificacion" name="clasificacion" readonly value="<?php echo $waste_data['clasificacion'] ?? ''; ?>">
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary"><?php echo $edit_mode ? '✓ Actualizar' : '➕ Guardar'; ?></button>
            <button type="button" class="btn btn-secondary" onclick="clearForm('waste-form')">🔄 Limpiar</button>
        </div>
    </form>
</div>

<div class="page-header">
    <h2>📊 Residuos Registrados</h2>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Grado</th>
                <th>Salón</th>
                <th>Tipo</th>
                <th>Peso (kg)</th>
                <th>Cantidad</th>
                <th>Estudiantes</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = executeQuery($conn, "SELECT * FROM residuos ORDER BY fecha DESC LIMIT 20");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $estado_class = $row['estado_residuo'] === 'Aprovechable' ? 'green' : 'red';
                    echo "<tr>
                        <td>{$row['fecha']}</td>
                        <td>{$row['grado']}</td>
                        <td>{$row['salon']}</td>
                        <td>{$row['tipo_residuo']}</td>
                        <td>{$row['peso_kg']}</td>
                        <td>{$row['cantidad']}</td>
                        <td>{$row['numero_estudiantes']}</td>
                        <td><span style='color: " . ($estado_class === 'green' ? '#27ae60' : '#e74c3c') . "'>●</span> {$row['estado_residuo']}</td>
                        <td>
                            <button onclick='editWaste({$row['id']})' class='btn btn-warning' style='padding: 0.5rem 1rem; font-size: 0.9rem;'>✎ Editar</button>
                            <button onclick='deleteWaste({$row['id']})' class='btn btn-danger' style='padding: 0.5rem 1rem; font-size: 0.9rem;'>🗑 Eliminar</button>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='9' style='text-align: center; color: #95a5a6;'>No hay registros aún</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
