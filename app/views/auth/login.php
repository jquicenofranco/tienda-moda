<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema Moda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-login {
            width: 100%;
            max-width: 400px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .login-header {
            background: #fff;
            border-radius: 15px 15px 0 0;
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="card card-login bg-white border-0">
    
    <div class="text-center login-header">
        <h3 class="fw-bold text-primary m-0">👗 Sistema Moda</h3>
        <small class="text-muted">Control de Acceso</small>
    </div>

    <div class="p-4">
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger text-center py-2 mb-3" role="alert">
                <small>📧 Correo o contraseña incorrectos</small>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/auth/acceder" method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold text-muted" style="font-size: 0.9rem;">Correo Electrónico</label>
                <input type="email" name="correo" class="form-control form-control-lg bg-light" 
                       placeholder="admin@tienda.com" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold text-muted" style="font-size: 0.9rem;">Contraseña</label>
                <input type="password" name="password" class="form-control form-control-lg bg-light" 
                       placeholder="••••••" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">INGRESAR AL SISTEMA</button>
            </div>
        </form>
        
        <div class="mt-4 text-center">
            <a href="#" class="text-decoration-none text-muted small">¿Olvidaste tu contraseña?</a>
        </div>
    </div>
</div>

</body>
</html>