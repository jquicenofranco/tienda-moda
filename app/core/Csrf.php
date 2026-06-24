<?php
// app/core/Csrf.php
// Helper para protección contra CSRF (Cross-Site Request Forgery).
// El token se almacena en la sesión y debe acompañar a toda petición POST.

class Csrf {

    /**
     * Devuelve el token de la sesión; lo genera la primera vez.
     */
    public static function token() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Campo oculto para insertar dentro de un <form>.
     */
    public static function field() {
        $token = htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8');
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }

    /**
     * Etiqueta <meta> para que el JavaScript pueda leer el token (fetch).
     */
    public static function meta() {
        $token = htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8');
        return '<meta name="csrf-token" content="' . $token . '">';
    }

    /**
     * Compara un token recibido con el de la sesión (a prueba de timing).
     */
    public static function validate($token) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $stored = $_SESSION['csrf_token'] ?? '';
        return is_string($token) && $stored !== '' && hash_equals($stored, $token);
    }
}
