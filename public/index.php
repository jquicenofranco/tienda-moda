<?php
// public/index.php

// 0. CARGAR VARIABLES DE ENTORNO (.env) si existe
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile) && is_readable($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        if (!str_contains($line, '=')) continue;
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key); $value = trim($value);
        // Quitar comillas envolventes si las hay
        $value = trim($value, "\"'");
        putenv("$key=$value");
        $_ENV[$key] = $value;
    }
}

// 0.1 INICIAR SESIÓN (global, antes de cualquier salida)
if (session_status() === PHP_SESSION_NONE) session_start();

// 1. CONFIGURACIÓN DE URL DINÁMICA
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
// APP_URL opcional (para entornos Docker donde la app vive en una subcarpeta).
// Si APP_BASE_PATH="/tienda_moda" -> BASE_URL será http://host/tienda_moda
// Por defecto se asume raíz (Docker).
$basePath = getenv('APP_BASE_PATH') ?: '';
define('BASE_URL', $protocol . '://' . $host . $basePath);

// 2. CARGA DE LIBRERÍAS DE COMPOSER (CRÍTICO: SOLUCIONA ERROR 'CLASS NOT FOUND')
require_once '../vendor/autoload.php';

// 3. REQUERIR BASE DE DATOS
require_once '../app/core/Database.php';

// 3.1 PROTECCIÓN CSRF
require_once '../app/core/Csrf.php';
// Asegura que el token exista en la sesión (disponible para vistas/JS).
Csrf::token();
// Validación centralizada: toda petición POST debe traer un token válido,
// ya sea en el campo de formulario 'csrf_token' o en la cabecera 'X-CSRF-Token'.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tokenRecibido = $_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
    if (!Csrf::validate($tokenRecibido)) {
        http_response_code(403);
        $esJson = isset($_SERVER['CONTENT_TYPE']) &&
                  stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;
        if ($esJson) {
            header('Content-Type: application/json');
            echo json_encode(['status' => false, 'error' => 'CSRF token inválido']);
        } else {
            echo 'Error 403: Token CSRF inválido o ausente.';
        }
        exit;
    }
}

// 4. AUTOLOAD (Carga automática de clases propias)
spl_autoload_register(function ($nombre_clase) {
    if (file_exists('../app/controllers/' . $nombre_clase . '.php')) {
        require_once '../app/controllers/' . $nombre_clase . '.php';
    } elseif (file_exists('../app/models/' . $nombre_clase . '.php')) {
        require_once '../app/models/' . $nombre_clase . '.php';
    }
});

// 5. SISTEMA DE RUTAS
$url = isset($_GET['url']) ? $_GET['url'] : 'Auth/index';
$url = rtrim($url, '/');
$url = explode('/', $url);

// Controlador
$controladorNombre = isset($url[0]) ? ucwords($url[0]) . 'Controller' : 'AuthController';
$metodo = isset($url[1]) ? $url[1] : 'index';
$parametros = isset($url[2]) ? array_slice($url, 2) : [];

// 6. EJECUTAR CONTROLADOR
if (file_exists('../app/controllers/' . $controladorNombre . '.php')) {
    $controlador = new $controladorNombre();
    
    if (method_exists($controlador, $metodo)) {
        call_user_func_array([$controlador, $metodo], $parametros);
    } else {
        echo "Error 404: El método no existe.";
    }
} else {
    header('Location: ' . BASE_URL . '/auth/index');
}