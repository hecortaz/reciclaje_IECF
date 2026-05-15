<?php
include 'config/database.php';
include 'includes/header.php';

$filter_type = isset($_GET['type']) ? sanitize($_GET['type'], $conn) : '';
$filter_start_date = isset($_GET['start_date']) ? sanitize($_GET['start_date'], $conn) : '';
$filter_end_date = isset($_GET['end_date']) ? sanitize($_GET['end_date'], $conn) : '';
$filter_grado = isset($_GET['grado']) ? sanitize($_GET['grado'], $conn) : '';

// Construir consulta con filtros
$query = "SELECT * FROM residuos WHERE 1=1";

if ($filter_type) {
    $query .= " AND tipo_residuo='$filter_type'";
}
if ($filter_grado) {
    $query .= " AND grado='$filter_grado'";
}
if ($filter_start_date) {
    $query .= " AND fecha >= '$filter_start_date'";
}
if ($filter_end_date) {
    $query .= " AND fecha <= '$filter_end_date'";
}

$query .= " ORDER BY fecha DESC";
$result = executeQuery($conn, $query);

// Calcular estadísticas filtradas
$total_peso = 0;
$total_aprovechable = 0;
$total_no_aprovechable = 0;
$total_registros = 0;
$total_estudiantes = 0;
$residuos_por_tipo = array();

while ($row = $result->fetch_assoc()) {
    $total_peso += $row['peso_kg'];
    $total_registros++;
    $total_estudiantes += $row['numero_estudiantes'];
    
    if ($row['estado_residuo'] === 'Aprovechable') {
        $total_aprovechable += $row['peso_kg'];
    } else {
        $total_no_aprovechable += $row['peso_kg'];
    }
    
    if (!isset($residuos_por_tipo[$row['tipo_residuo']])) {
        $residuos_por_tipo[$row['tipo_residuo']] = 0;
    }
    $residuos_por_tipo[$row['tipo_residuo']] += $row['peso_kg'];
}

// Reiniciar resultado para mostrar en tabla
$result = executeQuery($conn, $query);

$tasa_aprovechamiento = $total_peso > 0 ? round(($total_aprovechable / $total_peso) * 100, 2) : 0;
$generacion_per_capita = $total_estudiantes > 0 ? round($total_peso / $total_estudiantes, 2) : 0;
?>

<div class="page-header">
    <h1>📈 Reportes y Análisis</h1>
    <p>Genere reportes detallados con filtros personalizados</p>
</div>

<div class="form-container">
    <h3>🔍 Filtros de Búsqueda</h3>
    <form method="GET" class="form-row" style="gap: 1rem;">
        <div class="form-group" style="flex: 1;">
            <label for="tipo">Tipo de Residuo</label>
            <select id="tipo" name="type">
                <option value="">Todos</option>
                <option value="Papel" <?php echo $filter_type === 'Papel' ? 'selected' : ''; ?>>Papel</option>
                <option value="PET" <?php echo $filter_type === 'PET' ? 'selected' : ''; ?>>PET</option>
                <option value="Orgánico" <?php echo $filter_type === 'Orgánico' ? 'selected' : ''; ?>>Orgánico</option>
            </select>
        </div>
        <div class="form-group" style="flex: 1;">
            <label for="grado">Grado</label>
            <select id="grado" name="grado">
                <option value="">Todos</option>
                <option value="6°" <?php echo $filter_grado === '6°' ? 'selected' : ''; ?>>6°</option>
                <option value="7°" <?php echo $filter_grado === '7°' ? 'selected' : ''; ?>>7°</option>
                <option value="8°" <?php echo $filter_grado === '8°' ? 'selected' : ''; ?>>8°</option>
                <option value="9°" <?php echo $filter_grado === '9°' ? 'selected' : ''; ?>>9°</option>
                <option value="10°" <?php echo $filter_grado === '10°' ? 'selected' : ''; ?>>10°</option>
                <option value="11°" <?php echo $filter_grado === '11°' ? 'selected' : ''; ?>>11°</option>
            </select>
        </div>
        <div class="form-group" style="flex: 1;">
            <label for="start_date">Fecha Inicio</label>
            <input type="date" id="start_date" name="start_date" value="<?php echo $filter_start_date; ?>">
        </div>
        <div class="form-group" style="flex: 1;">
            <label for="end_date">Fecha Fin</label>
            <input type="date" id="end_date" name="end_date" value="<?php echo $filter_end_date; ?>">
        </div>
        <div style="display: flex; gap: 0.5rem; align-items: flex-end; flex: 1;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">🔍 Filtrar</button>
            <a href="reports.php" class="btn btn-secondary" style="text-decoration: none; text-align: center; flex: 1;">🔄 Limpiar</a>
        </div>
    </form>
</div>

<div class="cards-grid">
    <div class="card green">
        <div class="card-title">Total Residuos</div>
        <div class="card-value"><?php echo number_format($total_peso, 2); ?></div>
        <div class="card-unit">kg</div>
    </div>
    <div class="card blue">
        <div class="card-title">Aprovechables</div>
        <div class="card-value"><?php echo number_format($total_aprovechable, 2); ?></div>
        <div class="card-unit">kg</div>
    </div>
    <div class="card red">
        <div class="card-title">No Aprovechables</div>
        <div class="card-value"><?php echo number_format($total_no_aprovechable, 2); ?></div>
        <div class="card-unit">kg</div>
    </div>
    <div class="card orange">
        <div class="card-title">Tasa Aprovechamiento</div>
        <div class="card-value"><?php echo $tasa_aprovechamiento; ?></div>
        <div class="card-unit">%</div>
    </div>
    <div class="card purple">
        <div class="card-title">Generación Per Cápita</div>
        <div class="card-value"><?php echo $generacion_per_capita; ?></div>
        <div class="card-unit">kg/est</div>
    </div>
    <div class="card blue">
        <div class="card-title">Total Registros</div>
        <div class="card-value"><?php echo $total_registros; ?></div>
        <div class="card-unit">registros</div>
    </div>
</div>

<div class="page-header">
    <h2>📋 Detalle de Registros</h2>
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
                <th>Clasificación</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = executeQuery($conn, $query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $estado_color = $row['estado_residuo'] === 'Aprovechable' ? '#27ae60' : '#e74c3c';
                    echo "<tr>
                        <td>{$row['fecha']}</td>
                        <td>{$row['grado']}</td>
                        <td>{$row['salon']}</td>
                        <td>{$row['tipo_residuo']}</td>
                        <td>{$row['peso_kg']}</td>
                        <td>{$row['cantidad']}</td>
                        <td>{$row['numero_estudiantes']}</td>
                        <td><span style='color: $estado_color'>●</span> {$row['estado_residuo']}</td>
                        <td>{$row['clasificacion']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='9' style='text-align: center; color: #95a5a6;'>No hay registros que coincidan con los filtros</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php if ($total_peso > 0): ?>
<div class="page-header">
    <h2>📊 Porcentaje por Tipo de Residuo</h2>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Tipo de Residuo</th>
                <th>Peso (kg)</th>
                <th>Porcentaje</th>
                <th>Visualización</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($residuos_por_tipo as $tipo => $peso) {
                $porcentaje = round(($peso / $total_peso) * 100, 2);
                $bar_width = $porcentaje * 2;
                echo "<tr>
                    <td>$tipo</td>
                    <td>" . number_format($peso, 2) . " kg</td>
                    <td>$porcentaje%</td>
                    <td><div style='background: linear-gradient(90deg, #667eea 0%, #764ba2 100%); width: " . $bar_width . "px; height: 20px; border-radius: 3px;'></div></td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
