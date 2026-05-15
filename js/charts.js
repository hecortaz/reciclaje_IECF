// Función para crear gráfico de residuos por tipo
function createWasteTypeChart(canvasId, data) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.values,
                backgroundColor: [
                    '#667eea',
                    '#764ba2',
                    '#f093fb'
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

// Función para crear gráfico de residuos por salón
function createSalonChart(canvasId, data) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Peso (kg)',
                data: data.values,
                backgroundColor: '#667eea',
                borderColor: '#667eea',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: {
                    display: true
                }
            },
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Función para crear gráfico de aprovechamiento
function createUtilizationChart(canvasId, aprovechable, noAprovechable) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;
    
    const total = aprovechable + noAprovechable;
    const porcentajeAprovechable = (aprovechable / total * 100).toFixed(2);
    const porcentajeNoAprovechable = (noAprovechable / total * 100).toFixed(2);
    
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: [
                `Aprovechable (${porcentajeAprovechable}%)`,
                `No Aprovechable (${porcentajeNoAprovechable}%)`
            ],
            datasets: [{
                data: [aprovechable, noAprovechable],
                backgroundColor: [
                    '#27ae60',
                    '#e74c3c'
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

// Función para crear gráfico de línea temporal
function createTimelineChart(canvasId, labels, data) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Residuos (kg)',
                data: data,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}
