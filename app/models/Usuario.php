<?php
// app/models/Usuario.php
require_once '../app/core/Database.php';

class Usuario {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // LISTAR TODOS (Asegura la selección de las columnas correctas)
    public function listar() {
        // La consulta asume que la columna ahora se llama 'email'
        $sql = "SELECT id, nombre, email, password, rol, activo FROM usuarios ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // LOGIN
    public function login($correo, $password) {
        // La lógica de login usa 'correo' como parámetro de entrada, pero lo busca en la columna 'email'
        $sql = "SELECT * FROM usuarios WHERE email = :correo AND activo = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':correo' => $correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }
        return false;
    }

    // REGISTRAR NUEVO
    public function registrar($nombre, $correo, $password, $rol) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nombre, email, password, rol, activo) VALUES (:nom, :mail, :pass, :rol, 1)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':nom' => $nombre, ':mail' => $correo, ':pass' => $hash, ':rol' => $rol]);
    }

    // ACTUALIZAR
    public function actualizar($id, $nombre, $correo, $rol, $password = null) {
        if ($password) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET nombre = :nom, email = :mail, rol = :rol, password = :pass WHERE id = :id";
            $params = [':nom' => $nombre, ':mail' => $correo, ':rol' => $rol, ':pass' => $hash, ':id' => $id];
        } else {
            $sql = "UPDATE usuarios SET nombre = :nom, email = :mail, rol = :rol WHERE id = :id";
            $params = [':nom' => $nombre, ':mail' => $correo, ':rol' => $rol, ':id' => $id];
        }
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    // OBTENER POR ID
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // CAMBIAR ESTADO
    public function cambiarEstado($id, $estado) {
        $sql = "UPDATE usuarios SET activo = :estado WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':estado' => $estado, ':id' => $id]);
    }
    
    // CAMBIAR CLAVE (PERFIL)
    public function cambiarClave($id_usuario, $clave_actual, $clave_nueva) {
        $sql = "SELECT password FROM usuarios WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id_usuario]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($clave_actual, $usuario['password'])) {
            $hash_nuevo = password_hash($clave_nueva, PASSWORD_DEFAULT);
            $sqlUpdate = "UPDATE usuarios SET password = :pass WHERE id = :id";
            $stmtUpdate = $this->conn->prepare($sqlUpdate);
            return $stmtUpdate->execute([':pass' => $hash_nuevo, ':id' => $id_usuario]);
        }
        return false;
    }

    public function crear($nombre, $correo, $password) {
        $this->registrar($nombre, $correo, $password, 'admin');
    }
}