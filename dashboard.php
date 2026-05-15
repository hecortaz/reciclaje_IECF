<?php
include 'config/database.php';
include 'includes/header.php';

// Obtener estadísticas generales
$stats = array();

// Total de residuos
$result = executeQuery($conn, "SELECT SUM(peso_kg) as total FROM residuos");
$row = $result->fetch_assoc();
$stats['total_peso'] = $row['total'] ?? 0;

// Residuos aprovechables
$result = executeQuery($conn, "SELECT SUM(peso_kg) as total FROM residuos WHERE estado_residuo='Aprovechable'");
$row = $result->fetch_assoc();
$stats['aprovechables'] = $row['total'] ?? 0;

// Residuos no aprovechables
$result = executeQuery($conn, "SELECT SUM(peso_kg) as total FROM residuos WHERE estado_residuo='No aprovechable'");
$row = $result->fetch_assoc();
$stats['no_aprovechables'] = $row['total'] ?? 0;

// Cantidad de registros
$result = executeQuery($conn, "SELECT COUNT(*) as total FROM residuos");
$row = $result->fetch_assoc();
$stats['total_registros'] = $row['total'] ?? 0;

// Tasa de aprovechamiento
$stats['tasa_aprovechamiento'] = $stats['total_peso'] > 0 ? 
    round(($stats['aprovechables'] / $stats['total_peso']) * 100, 2) : 0;

// Total de estudiantes
$result = executeQuery($conn, "SELECT SUM(numero_estudiantes) as total FROM residuos");
$row = $result->fetch_assoc();
$stats['total_estudiantes'] = $row['total'] ?? 0;

// Generación per cápita
$stats['generacion_per_capita'] = $stats['total_estudiantes'] > 0 ? 
    round($stats['total_peso'] / $stats['total_estudiantes'], 2) : 0;

// Residuos por tipo
$result = executeQuery($conn, "SELECT tipo_residuo, SUM(peso_kg) as total FROM residuos GROUP BY tipo_residuo");
$tipos = array();
while ($row = $result->fetch_assoc()) {
    $tipos[$row['tipo_residuo']] = $row['total'];
}

// Residuos por salón
$result = executeQuery($conn, "SELECT salon, SUM(peso_kg) as total FROM residuos GROUP BY salon ORDER BY total DESC");
$salones = array();
while ($row = $result->fetch_assoc()) {
    $salones[$row['salon']] = $row['total'];
}

// Residuos por grado
$result = executeQuery($conn, "SELECT grado, SUM(peso_kg) as total FROM residuos GROUP BY grado ORDER BY total DESC");
$grados = array();
while ($row = $result->fetch_assoc()) {
    $grados[$row['grado']] = $row['total'];
}
?>

<div class="page-header">
    <h1>📊 Dashboard de Control</h1>
    <p>Visualice los indicadores clave de gestión de reciclaje</p>
</div>

<div class="cards-grid">
    <div class="card green">
        <div class="card-title">📦 Total de Residuos</div>
        <div class="card-value"><?php echo number_format($stats['total_peso'], 2); ?></div>
        <div class="card-unit">kg</div>
    </div>
    <div class="card blue">
        <div class="card-title">✓ Aprovechables</div>
        <div class="card-value"><?php echo number_format($stats['aprovechables'], 2); ?></div>
        <div class="card-unit">kg</div>
    </div>
    <div class="card red">
        <div class="card-title">✗ No Aprovechables</div>
        <div class="card-value"><?php echo number_format($stats['no_aprovechables'], 2); ?></div>
        <div class="card-unit">kg</div>
    </div>
    <div class="card orange">
        <div class="card-title">📈 Tasa Aprovechamiento</div>
        <div class="card-value"><?php echo $stats['tasa_aprovechamiento']; ?></div>
        <div class="card-unit">%</div>
    </div>
    <div class="card purple">
        <div class="card-title">👥 Generación Per Cápita</div>
        <div class="card-value"><?php echo $stats['generacion_per_capita']; ?></div>
        <div class="card-unit">kg/estudiante</div>
    </div>
    <div class="card green">
        <div class="card-title">📋 Total Registros</div>
        <div class="card-value"><?php echo $stats['total_registros']; ?></div>
        <div class="card-unit">registros</div>
    </div>
</div>

<div class="charts-grid">
    <div class="chart-container">
        <h3>Residuos por Tipo</h3>
        <canvas id="wasteTypeChart"></canvas>
    </div>
    <div class="chart-container">
        <h3>Aprovechamiento</h3>
        <canvas id="utilizationChart"></canvas>
    </div>
</div>

<div class="charts-grid">
    <div class="chart-container">
        <h3>Top Salones por Generación</h3>
        <canvas id="salonChart"></canvas>
    </div>
    <div class="chart-container">
        <h3>Residuos por Grado</h3>
        <canvas id="gradeChart"></canvas>
    </div>
</div>

<div class="page-header">
    <h2>📌 Hallazgos Principales</h2>
</div>

<div class="cards-grid">
    <?php
    // Grado con más residuos
    if (!empty($grados)) {
        $max_grado = array_key_first($grados);
        echo "<div class='card'>
            <div class='card-title'>Grado Mayor Generación</div>
            <div class='card-value' style='font-size: 1.8rem; color: #e74c3c;'>" . $max_grado . "</div>
            <div class='card-unit'>" . number_format($grados[$max_grado], 2) . " kg</div>
        </div>";
    }
    
    // Salón con más residuos
    if (!empty($salones)) {
        $max_salon = array_key_first($salones);
        echo "<div class='card'>
            <div class='card-title'>Salón Mayor Generación</div>
            <div class='card-value' style='font-size: 1.8rem; color: #3498db;'>" . $max_salon . "</div>
            <div class='card-unit'>" . number_format($salones[$max_salon], 2) . " kg</div>
        </div>";
    }
    
    // Tipo de residuo más común
    if (!empty($tipos)) {
        $max_tipo = array_key_first($tipos);
        $porcentaje_tipo = ($tipos[$max_tipo] / $stats['total_peso']) * 100;
        echo "<div class='card'>
            <div class='card-title'>Residuo Más Común</div>
            <div class='card-value' style='font-size: 1.8rem; color: #27ae60;'>" . $max_tipo . "</div>
            <div class='card-unit'>" . round($porcentaje_tipo, 2) . "% del total</div>
        </div>";
    }
    ?>
</div>

<script>
    // Datos para gráficos
    const wasteTypeData = {
        labels: <?php echo json_encode(array_keys($tipos)); ?>,
        values: <?php echo json_encode(array_values($tipos)); ?>
    };
    
    const salonData = {
        labels: <?php echo json_encode(array_keys($salones)); ?>,
        values: <?php echo json_encode(array_values($salones)); ?>
    };
    
    const gradeData = {
        labels: <?php echo json_encode(array_keys($grados)); ?>,
        values: <?php echo json_encode(array_values($grados)); ?>
    };
    
    // Crear gráficos
    createWasteTypeChart('wasteTypeChart', wasteTypeData);
    createUtilizationChart('utilizationChart', <?php echo $stats['aprovechables']; ?>, <?php echo $stats['no_aprovechables']; ?>);
    createSalonChart('salonChart', salonData);
    createSalonChart('gradeChart', gradeData);
</script>

<?php include 'includes/footer.php'; ?>
