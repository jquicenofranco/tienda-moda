<?php
require_once '../app/core/Database.php';

class Reporte {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // 1. Total vendido HOY
    public function ventasHoy() {
        $sql = "SELECT SUM(total) as total, COUNT(*) as transacciones FROM ventas WHERE DATE(fecha) = CURDATE()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 2. Ventas de los últimos 7 días (Para el gráfico de líneas)
    public function ventasUltimos7Dias() {
        $sql = "SELECT DATE(fecha) as fecha, SUM(total) as total 
                FROM ventas 
                WHERE fecha >= DATE(NOW()) - INTERVAL 7 DAY
                GROUP BY DATE(fecha)
                ORDER BY fecha ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Top 5 Productos más vendidos (Para el gráfico de torta/barras)
    public function productosMasVendidos() {
        $sql = "SELECT p.nombre, SUM(d.cantidad) as cantidad
                FROM venta_detalles d
                JOIN producto_variantes v ON d.variante_id = v.id
                JOIN productos p ON v.producto_id = p.id
                GROUP BY p.id
                ORDER BY cantidad DESC
                LIMIT 5";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}