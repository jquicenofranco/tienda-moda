<?php
require_once '../app/models/Cliente.php';

class ClienteController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/auth/index');
            exit;
        }
    }

    public function index() {
        $clienteModel = new Cliente();
        $clientes = $clienteModel->listar();
        require_once '../app/views/clientes/index.php';
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $clienteModel = new Cliente();
            $id = $_POST['id'] ?? '';
            $datos = [
                'nombre' => $_POST['nombre'],
                'documento' => $_POST['documento'],
                'telefono' => $_POST['telefono'],
                'correo' => $_POST['correo'],
                'direccion' => $_POST['direccion']
            ];

            if (empty($id)) {
                $clienteModel->registrar($datos);
            } else {
                $clienteModel->actualizar($id, $datos);
            }
            header('Location: ' . BASE_URL . '/cliente/index?msg=success');
        }
    }

    // API JSON para el Modal de Edición
    public function obtener($id) {
        $clienteModel = new Cliente();
        echo json_encode($clienteModel->obtenerPorId($id));
    }

    // API JSON para buscar desde el TPV
    public function buscar($termino) {
        $clienteModel = new Cliente();
        echo json_encode($clienteModel->buscar($termino));
    }
}