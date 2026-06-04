<?php
// app/models/Producto.php
require_once '../app/core/Database.php';

class Producto {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // 1. LISTAR (CORREGIDO: Ahora incluye precio_compra)
    public function listar() {
        $sql = "SELECT p.id, p.nombre, p.precio_compra, p.precio_venta, p.categoria_id, p.activo,
                c.nombre as categoria_nombre, 
                (SELECT IFNULL(SUM(stock_actual), 0) FROM producto_variantes WHERE producto_id = p.id) as stock_total
                FROM productos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                ORDER BY p.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. REGISTRAR (Producto + Variantes)
    public function registrar($datos, $variantes) {
        try {
            $this->conn->beginTransaction();

            $sql = "INSERT INTO productos (nombre, codigo_barras_base, categoria_id, precio_compra, precio_venta, descripcion, activo) 
                    VALUES (:nom, :cod, :cat, :p_compra, :p_venta, :desc, 1)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':nom' => $datos['nombre'],
                ':cod' => !empty($datos['codigo']) ? $datos['codigo'] : null,
                ':cat' => $datos['categoria'],
                ':p_compra' => !empty($datos['precio_compra']) ? $datos['precio_compra'] : 0,
                ':p_venta' => $datos['precio_venta'],
                ':desc' => $datos['descripcion']
            ]);
            
            $producto_id = $this->conn->lastInsertId();

            $sqlVar = "INSERT INTO producto_variantes (producto_id, talla, color, stock_actual, codigo_barras_variante) 
                       VALUES (:pid, :talla, :color, :stock, :cod_var)";
            $stmtVar = $this->conn->prepare($sqlVar);

            foreach($variantes as $v) {
                $codigo_var = !empty($v['codigo']) ? $v['codigo'] : $producto_id . '-' . $v['talla'] . '-' . substr($v['color'], 0, 3);
                $stmtVar->execute([
                    ':pid' => $producto_id,
                    ':talla' => $v['talla'],
                    ':color' => $v['color'],
                    ':stock' => $v['stock'],
                    ':cod_var' => strtoupper($codigo_var)
                ]);
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    // 3. OBTENER VARIANTES
    public function obtenerVariantes($id_producto) {
        $sql = "SELECT id, talla, color, stock_actual, codigo_barras_variante 
                FROM producto_variantes 
                WHERE producto_id = :pid";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':pid' => $id_producto]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 4. OBTENER UN PRODUCTO
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM productos WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 5. ACTUALIZAR
    public function actualizar($id, $datos, $variantes_update = []) {
        try {
            $this->conn->beginTransaction();

            $sql = "UPDATE productos SET 
                    nombre = :nombre, codigo_barras_base = :cod, categoria_id = :cat, 
                    precio_compra = :pcompra, precio_venta = :pventa, descripcion = :desc 
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':nombre' => $datos['nombre'], ':cod' => $datos['codigo'], ':cat' => $datos['categoria'],
                ':pcompra' => $datos['precio_compra'], ':pventa' => $datos['precio_venta'],
                ':desc' => $datos['descripcion'], ':id' => $id
            ]);

            if (!empty($variantes_update)) {
                $sqlVar = "UPDATE producto_variantes SET stock_actual = :stock, codigo_barras_variante = :cod 
                           WHERE id = :vid AND producto_id = :pid";
                $stmtVar = $this->conn->prepare($sqlVar);

                foreach ($variantes_update as $v) {
                    $stmtVar->execute([
                        ':stock' => $v['stock'], ':cod' => $v['codigo'], ':vid' => $v['id_variante'], ':pid' => $id
                    ]);
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // 6. CAMBIAR ESTADO
    public function cambiarEstado($id, $nuevo_estado) {
        $sql = "UPDATE productos SET activo = :estado WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':estado' => $nuevo_estado, ':id' => $id]);
    }
}