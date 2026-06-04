<?php
// app/controllers/GastoController.php
require_once '../app/models/Gasto.php';
require_once '../app/models/Caja.php';

class GastoController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) { header('Location: ' . BASE_URL . '/auth/index'); exit; }
    }

    public function index() {
        $cajaModel = new Caja();
        $gastoModel = new Gasto();
        
        $caja = $cajaModel->obtenerCajaAbierta($_SESSION['user_id']);

        if (!$caja) { header('Location: ' . BASE_URL . '/caja/index'); exit; }

        // FIX: Obtener total y lista de gastos para la sesión actual
        $gastos = $gastoModel->listarPorSesion($caja['id']);
        $totalHoy = $gastoModel->totalGastosSesion($caja['id']); 

        require_once '../app/views/gastos/index.php';
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $cajaModel = new Caja();
            $caja = $cajaModel->obtenerCajaAbierta($_SESSION['user_id']);

            if ($caja) {
                $gastoModel = new Gasto();
                $desc = $_POST['descripcion'];
                $monto = $_POST['monto'];
                $usuario_id = $_SESSION['user_id'];
                
                $gastoModel->registrar($caja['id'], $desc, $monto, $usuario_id);
            }
            header('Location: ' . BASE_URL . '/gasto/index?msg=success');
        }
    }
}