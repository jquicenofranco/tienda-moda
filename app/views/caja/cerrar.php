<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cierre de Caja - Sistema Moda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        /* FIX DE LAYOUT CRÍTICO: Igual que en los otros módulos */
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
    <div class="d-flex align-items-center justify-content-center min-vh-100 p-4">
        
        <div class="card shadow border-0 w-100" style="max-width: 600px;">
            <div class="card-header bg-danger text-white text-center py-3">
                <h4 class="m-0 fw-bold"><i class="bi bi-lock-fill"></i> Cierre de Caja</h4>
            </div>
            <div class="card-body p-4">
                
                <div class="alert alert-info shadow-sm mb-4">
                    <h5 class="alert-heading text-center fw-bold mb-3">Resumen del Turno</h5>
                    
                    <div class="d-flex justify-content-between mb-1 border-bottom pb-1">
                        <span class="text-muted">Monto Apertura (Base):</span>
                        <span class="fw-bold">S/ <?= number_format($caja['monto_apertura'], 2) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-1 border-bottom pb-1">
                        <span class="text-muted">Ventas Realizadas (+):</span>
                        <span class="fw-bold text-success">S/ <?= number_format($ventas_sesion, 2) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 pb-1">
                        <span class="text-muted">Gastos / Salidas (-):</span>
                        <span class="fw-bold text-danger">S/ <?= number_format($gastos_sesion, 2) ?></span>
                    </div>
                    
                    <div class="d-flex justify-content-between fs-5 bg-white p-2 rounded border">
                        <span class="fw-bold text-dark">Total Esperado:</span>
                        <span class="fw-bold text-primary">
                            S/ <?= number_format(($caja['monto_apertura'] + $ventas_sesion) - $gastos_sesion, 2) ?>
                        </span>
                    </div>
                </div>

                <form action="<?= BASE_URL ?>/caja/cerrar" method="POST">
                    <?= Csrf::field() ?>
                    <input type="hidden" name="id_sesion" value="<?= $caja['id'] ?>">
                    <input type="hidden" name="total_ventas" value="<?= $ventas_sesion ?>">
                    <input type="hidden" name="total_gastos" value="<?= $gastos_sesion ?>">

                    <div class="mb-4">
                        <label class="form-label fw-bold">¿Cuánto dinero hay FÍSICAMENTE en el cajón?</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light">💰 S/</span>
                            <input type="number" step="0.01" name="monto_fisico" class="form-control fw-bold text-center" required placeholder="0.00" style="font-size: 1.5rem;">
                        </div>
                        <div class="form-text text-center mt-2">Ingresa el monto real contado. El sistema calculará si sobra o falta dinero.</div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger btn-lg shadow">
                            CONFIRMAR CIERRE DE CAJA
                        </button>
                        <a href="<?= BASE_URL ?>/ventas" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar y volver a vender
                        </a>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>