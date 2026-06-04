<?php
// app/controllers/HomeController.php

class HomeController {
    public function index() {
        // Redirigir automáticamente al Login
        // Usamos una ruta relativa para que funcione tanto en localhost como en tienda_moda.test
        header('Location: auth/index');
    }
}