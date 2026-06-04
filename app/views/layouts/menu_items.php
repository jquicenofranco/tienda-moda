<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item mb-1"><a href="<?= BASE_URL ?>/ventas" class="nav-link bg-success text-white shadow"><i class="bi bi-cart4 me-2"></i> IR A VENDER (TPV)</a></li>
    <li class="nav-item mb-1"><a href="<?= BASE_URL ?>/ventas/historial" class="nav-link text-white"><i class="bi bi-clock-history me-2"></i> Historial Ventas</a></li>
    <li class="nav-item mb-1"><a href="<?= BASE_URL ?>/gasto/index" class="nav-link text-white"><i class="bi bi-cash-coin me-2"></i> Gastos / Salidas</a></li>
    <li class="nav-item mb-3"><a href="<?= BASE_URL ?>/caja/index" class="nav-link text-danger"><i class="bi bi-wallet2 me-2"></i> Cerrar Caja</a></li>

    <div class="text-uppercase text-muted fw-bold small mb-2" style="font-size: 11px; letter-spacing: 1px;">Gestión</div>

    <li class="nav-item">
        <a href="<?= BASE_URL ?>/producto/index" class="nav-link text-white d-flex justify-content-between align-items-center">
            <div><i class="bi bi-box-seam me-2"></i> Inventario</div>
            <?php if(isset($stock_bajo) && $stock_bajo > 0): ?><span class="badge bg-danger rounded-pill"><?= $stock_bajo ?></span><?php endif; ?>
        </a>
    </li>
    
    <?php if(isset($_SESSION['user_rol']) && $_SESSION['user_rol'] == 'admin'): ?>
        <li><a href="<?= BASE_URL ?>/producto/crear" class="nav-link text-white"><i class="bi bi-plus-circle me-2"></i> Nuevo Producto</a></li>
    <?php endif; ?>

    <li><a href="<?= BASE_URL ?>/cliente/index" class="nav-link text-white"><i class="bi bi-people me-2"></i> Clientes</a></li>
    <li><a href="<?= BASE_URL ?>/etiqueta/index" class="nav-link text-white"><i class="bi bi-upc-scan me-2"></i> Códigos de Barra</a></li>
    <li><a href="<?= BASE_URL ?>/movimiento/index" class="nav-link text-white"><i class="bi bi-arrow-left-right me-2"></i> Mermas</a></li>
    <li><a href="<?= BASE_URL ?>/reporte/index" class="nav-link text-white"><i class="bi bi-bar-chart-fill me-2"></i> Reportes</a></li>

    <?php if(isset($_SESSION['user_rol']) && $_SESSION['user_rol'] == 'admin'): ?>
        <li class="mt-3 border-top pt-2">
            <div class="text-uppercase text-muted fw-bold small mb-2" style="font-size: 11px; letter-spacing: 1px;">Administración</div>
            <a href="<?= BASE_URL ?>/compra/crear" class="nav-link text-white fw-bold"><i class="bi bi-cart-plus-fill me-2"></i> Ingresar Compra</a>
            <a href="<?= BASE_URL ?>/proveedor/index" class="nav-link text-white"><i class="bi bi-truck me-2"></i> Proveedores</a>
            <a href="<?= BASE_URL ?>/usuario/index" class="nav-link text-white"><i class="bi bi-people-fill me-2"></i> Equipo / Usuarios</a>
            <a href="<?= BASE_URL ?>/config/index" class="nav-link text-white"><i class="bi bi-gear-fill me-2"></i> Configuración</a>
        </li>
    <?php endif; ?>
</ul>

<hr>

<div class="dropdown dropup">
    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
        <div class="bg-primary rounded-circle d-flex justify-content-center align-items-center me-2 text-uppercase fw-bold" style="width: 32px; height: 32px;">
            <?= substr($_SESSION['user_nombre'] ?? 'U', 0, 1) ?>
        </div>
        <strong><?= $_SESSION['user_nombre'] ?? 'Usuario' ?></strong>
    </a>
    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
        <li><a class="dropdown-item" href="<?= BASE_URL ?>/perfil/index"><i class="bi bi-person-gear me-2"></i> Mi Perfil</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/auth/logout"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a></li>
    </ul>
</div>