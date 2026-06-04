<?php
require_once '../app/models/Reporte.php';

class ReporteController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: http://localhost/tienda_moda/auth/index');
            exit;
        }
    }

    public function index() {
        $reporteModel = new Reporte();
        
        // Obtener todos los datos necesarios
        $hoy = $reporteModel->ventasHoy();
        $historial = $reporteModel->ventasUltimos7Dias();
        $topProductos = $reporteModel->productosMasVendidos();

        // Convertir datos a formato JSON para que JavaScript los pueda leer fácilmente
        $dataHistorial = json_encode($historial);
        $dataTop = json_encode($topProductos);

        require_once '../app/views/reportes/index.php';
    }
}