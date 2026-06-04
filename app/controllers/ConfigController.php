<?php
require_once '../app/models/Empresa.php';

class ConfigController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        // Solo Admin puede tocar esto
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] != 'admin') {
            header('Location: ' . BASE_URL . '/ventas');
            exit;
        }
    }

    public function index() {
        $empresaModel = new Empresa();
        $datos = $empresaModel->obtener();
        require_once '../app/views/config/index.php';
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $empresaModel = new Empresa();
            
            $datos = [
                'nombre' => $_POST['nombre'],
                'ruc' => $_POST['ruc'],
                'direccion' => $_POST['direccion'],
                'telefono' => $_POST['telefono'],
                'email' => $_POST['email'],
                'mensaje' => $_POST['mensaje']
            ];

            if ($empresaModel->actualizar($datos)) {
                header('Location: ' . BASE_URL . '/config/index?msg=success');
            } else {
                echo "Error al guardar configuración";
            }
        }
    }
}