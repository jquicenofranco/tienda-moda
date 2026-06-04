<?php
require_once '../app/models/Compra.php';
require_once '../app/models/Proveedor.php';

class CompraController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        // Solo Admin
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] != 'admin') {
            header('Location: ' . BASE_URL . '/ventas');
            exit;
        }
    }

    // VISTA DE COMPRA (Nueva Compra)
    public function crear() {
        $proveedorModel = new Proveedor();
        $proveedores = $proveedorModel->listar();
        require_once '../app/views/compras/crear.php';
    }

    // PROCESAR GUARDADO
    public function guardar() {
        header('Content-Type: application/json');
        
        // Recibir JSON desde JS
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['carrito']) && count($data['carrito']) > 0) {
            $compraModel = new Compra();
            
            try {
                $compraModel->registrar(
                    $_SESSION['user_id'],
                    $data['proveedor_id'],
                    $data['comprobante'],
                    $data['carrito'],
                    $data['total']
                );
                echo json_encode(['status' => true]);
            } catch (Exception $e) {
                echo json_encode(['status' => false, 'message' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['status' => false, 'message' => 'Carrito vacío']);
        }
    }
}