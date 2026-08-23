<?php
session_start();
require_once '../config.php';

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT id, usuario, password, nombre FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_nombre'] = $row['nombre'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Contraseña incorrecta';
        }
    } else {
        $error = 'Usuario no encontrado';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CONSOLID Admin</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--azul-oscuro), var(--celeste));">
        <div style="background: white; padding: 3rem; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); width: 100%; max-width: 400px;">
            <h2 style="text-align: center; color: var(--azul-oscuro); margin-bottom: 2rem;">CONSOLID Admin</h2>
            
            <?php if ($error): ?>
                <p style="color: red; text-align: center; margin-bottom: 1rem;"><?php echo $error; ?></p>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Usuario</label>
                    <input type="text" name="usuario" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%;">Ingresar</button>
            </form>
            
            <p style="text-align: center; margin-top: 1rem;">
                <a href="../index.php" style="color: var(--celeste);">← Volver al sitio</a>
            </p>
        </div>
    </div>
</body>
</html>