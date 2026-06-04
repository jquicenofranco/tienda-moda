<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes - Sistema Moda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        /* FIX DE LAYOUT CRÍTICO: Asegura que el contenido se mueva a la derecha del sidebar fijo */
        @media (min-width: 992px) {
            .offcanvas-lg { position: fixed !important; top: 0; }
            .main-content-area { 
                margin-left: 260px; /* Ancho del sidebar */
                width: calc(100% - 260px);
            }
        }
        @media (max-width: 767px) { .offcanvas-open { overflow: hidden !important; } }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark d-lg-none shadow-sm">
    <div class="container-fluid">
        <span class="navbar-brand fw-bold">Sistema Moda</span>
        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
            <i class="bi bi-list"></i> Menú
        </button>
    </div>
</nav>

<?php include '../app/views/layouts/sidebar.php'; ?>

<div class="main-content-area" style="height: 100vh; overflow-y: auto;">
    <div class="container-fluid p-4">
        
        <h2 class="fw-bold mb-4"><i class="bi bi-bar-chart-line"></i> Reporte de Ventas</h2>

        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="card shadow-sm border-0 bg-primary text-white h-100">
                    <div class="card-body">
                        <h5 class="card-title opacity-75">Ventas de Hoy</h5>
                        <h2 class="display-4 fw-bold">S/ <?= number_format($hoy['total'] ?? 0, 2) ?></h2>
                        <p class="mb-0"><i class="bi bi-receipt"></i> <?= $hoy['transacciones'] ?? 0 ?> tickets generados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm border-0 bg-success text-white h-100">
                    <div class="card-body">
                        <h5 class="card-title opacity-75">Estado del Sistema</h5>
                        <h3 class="mt-3">🟢 Operativo</h3>
                        <p class="mb-0">Fecha: <?= date('d/m/Y') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white fw-bold">Evolución de Ventas (Últimos 7 días)</div>
                    <div class="card-body">
                        <canvas id="chartVentas"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white fw-bold">Top Productos</div>
                    <div class="card-body">
                        <canvas id="chartProductos"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // 1. Recibir datos de PHP (Aseguramos arrays vacíos si no hay datos)
    const historial = <?= $dataHistorial ?? '[]' ?>;
    const topProd = <?= $dataTop ?? '[]' ?>;

    // 2. Configurar Gráfico de Líneas (Ventas)
    const ctxVentas = document.getElementById('chartVentas');
    if (ctxVentas) {
        new Chart(ctxVentas, {
            type: 'line',
            data: {
                labels: historial.map(item => item.fecha),
                datasets: [{
                    label: 'Ventas en Soles (S/)',
                    data: historial.map(item => item.total),
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    fill: true,
                    tension: 0.4 
                }]
            },
            options: { 
                scales: { y: { beginAtZero: true } },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // 3. Configurar Gráfico de Torta (Productos)
    const ctxProd = document.getElementById('chartProductos');
    if (ctxProd) {
        new Chart(ctxProd, {
            type: 'doughnut',
            data: {
                labels: topProd.map(item => item.nombre),
                datasets: [{
                    data: topProd.map(item => item.cantidad),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
</script>

</body>
</html>