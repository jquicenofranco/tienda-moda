<?php
require_once '../app/core/Database.php';

class Compra {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function registrar($usuario_id, $proveedor_id, $comprobante, $carrito, $total) {
        try {
            $this->conn->beginTransaction();

            // 1. Insertar Cabecera
            $sql = "INSERT INTO compras (usuario_id, proveedor_id, numero_comprobante, total, fecha) 
                    VALUES (:uid, :prov, :comp, :total, NOW())";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':uid' => $usuario_id,
                ':prov' => $proveedor_id,
                ':comp' => $comprobante,
                ':total' => $total
            ]);
            $compra_id = $this->conn->lastInsertId();

            // Preparar consultas repetitivas
            $sqlDetalle = "INSERT INTO compra_detalles (compra_id, variante_id, cantidad, precio_compra, subtotal) 
                           VALUES (:cid, :vid, :cant, :costo, :sub)";
            $stmtDetalle = $this->conn->prepare($sqlDetalle);

            // Actualizar Stock (SUMAR)
            $sqlStock = "UPDATE producto_variantes SET stock_actual = stock_actual + :cant WHERE id = :vid";
            $stmtStock = $this->conn->prepare($sqlStock);

            // Actualizar Precio de Costo en el Producto Padre (Opcional, pero recomendado)
            // Se actualiza al último precio de compra registrado
            $sqlPrecio = "UPDATE productos p 
                          JOIN producto_variantes v ON v.producto_id = p.id 
                          SET p.precio_compra = :costo 
                          WHERE v.id = :vid";
            $stmtPrecio = $this->conn->prepare($sqlPrecio);

            // Insertar Kardex (ENTRADA)
            $sqlKardex = "INSERT INTO kardex (variante_id, tipo, cantidad, descripcion, usuario_id, fecha) 
                          VALUES (:vid, 'entrada', :cant, :desc, :uid, NOW())";
            $stmtKardex = $this->conn->prepare($sqlKardex);

            // 2. Procesar Productos
            foreach ($carrito as $item) {
                // Guardar Detalle
                $stmtDetalle->execute([
                    ':cid' => $compra_id,
                    ':vid' => $item['variante_id'],
                    ':cant' => $item['cantidad'],
                    ':costo' => $item['costo'],
                    ':sub' => $item['costo'] * $item['cantidad']
                ]);

                // Sumar Stock
                $stmtStock->execute([
                    ':cant' => $item['cantidad'],
                    ':vid' => $item['variante_id']
                ]);

                // Actualizar Costo del Producto
                $stmtPrecio->execute([
                    ':costo' => $item['costo'],
                    ':vid' => $item['variante_id']
                ]);

                // Registrar en Kardex
                $stmtKardex->execute([
                    ':vid' => $item['variante_id'],
                    ':cant' => $item['cantidad'],
                    ':desc' => "Compra $comprobante (ID: $compra_id)",
                    ':uid' => $usuario_id
                ]);
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            throw new Exception($e->getMessage());
        }
    }
}