<?php
// app/models/Gasto.php
require_once '../app/core/Database.php';

class Gasto {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // 1. Listar gastos de la sesión de caja actual (Ahora con JOIN a usuarios)
    public function listarPorSesion($id_sesion) {
        $sql = "SELECT g.*, u.nombre as usuario_nombre 
                FROM gastos g
                JOIN usuarios u ON g.usuario_id = u.id
                WHERE caja_sesion_id = :id ORDER BY g.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id_sesion]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Registrar nuevo gasto (Ahora recibe usuario_id)
    public function registrar($id_sesion, $descripcion, $monto, $usuario_id) {
        $sql = "INSERT INTO gastos (caja_sesion_id, descripcion, monto, fecha, usuario_id) 
                VALUES (:id, :desc, :monto, NOW(), :uid)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id_sesion,
            ':desc' => $descripcion,
            ':monto' => $monto,
            ':uid' => $usuario_id // Nuevo parámetro aquí
        ]);
    }

    // ... (totalGastosSesion se mantiene igual) ...
    public function totalGastosSesion($id_sesion) {
        $sql = "SELECT IFNULL(SUM(monto), 0) FROM gastos WHERE caja_sesion_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id_sesion]);
        return $stmt->fetchColumn();
    }
}