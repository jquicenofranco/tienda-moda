<?php
require_once '../app/core/Database.php';
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->query("SELECT COUNT(*) FROM producto_variantes v JOIN productos p ON v.producto_id = p.id WHERE v.stock_actual <= 5 AND p.activo = 1");
$stock_bajo = $stmt->fetchColumn();
?>

<div class="offcanvas offcanvas-start bg-dark text-white d-lg-none" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title fw-bold" id="sidebarMenuLabel">Sistema Moda</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column p-3">
        <?php include 'menu_items.php'; ?> </div>
</div>

<div class="d-none d-lg-flex flex-column flex-shrink-0 p-3 text-white bg-dark" 
     style="width: 260px; height: 100vh; position: fixed; top: 0; left: 0; overflow-y: auto; z-index: 1000;">
    
    <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <i class="bi bi-shop fs-4 me-2"></i>
        <span class="fs-4 fw-bold">Sistema Moda</span>
    </a>
    <hr>
    
    <?php include 'menu_items.php'; ?>
</div>