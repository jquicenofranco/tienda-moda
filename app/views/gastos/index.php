<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Gastos - Sistema Moda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* FIX DE LAYOUT CRÍTICO: Igual que en Inventario e Historial */
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
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0"><i class="bi bi-cash-coin"></i> Gestión de Gastos / Salidas</h2>
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalGasto">
                <i class="bi bi-dash-circle"></i> Registrar Gasto
            </button>
        </div>

        <?php if(isset($_GET['msg'])): ?>
            <div class="alert alert-info alert-dismissible fade show"><i class="bi bi-check-circle-fill"></i> Gasto registrado correctamente.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 bg-warning text-dark">
                    <div class="card-body">
                        <h5 class="card-title opacity-75">Total Gastos Hoy</h5>
                        <h2 class="display-6 fw-bold">S/ <?= number_format($totalHoy ?? 0, 2) ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold">Gastos Registrados en este Turno</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Fecha/Hora</th>
                                <th>Monto</th>
                                <th>Motivo / Descripción</th>
                                <th>Registrado Por</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($gastos)): ?>
                                <tr><td colspan="5" class="text-center py-4 text-muted"><h4>No hay gastos registrados en este turno.</h4></td></tr>
                            <?php else: ?>
                                <?php foreach($gastos as $g): ?>
                                <tr>
                                    <td><?= $g['id'] ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($g['fecha'])) ?></td>
                                    <td class="fw-bold text-danger">- S/ <?= number_format($g['monto'], 2) ?></td>
                                    <td><?= $g['descripcion'] ?></td>
                                    <td><i class="bi bi-person"></i> <?= $g['usuario_nombre'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="modalGasto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-dash-circle"></i> Registrar Salida de Dinero</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASE_URL ?>/gasto/guardar" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="monto" class="form-label fw-bold">Monto a Retirar (S/)</label>
                        <div class="input-group">
                            <span class="input-group-text">S/</span>
                            <input type="number" step="0.01" min="0.01" class="form-control fw-bold" id="monto" name="monto" required placeholder="0.00">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Motivo / Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required placeholder="Ej: Pago de almuerzo, Compra de útiles, Pasajes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Guardar Gasto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>