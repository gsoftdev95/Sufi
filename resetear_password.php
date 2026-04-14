<?php

require_once('./helpers/dd.php');
require_once('./controllers/functions.php');
require_once('./src/partials/conexionBD.php');

$errores = [];

// Validar token en URL
if (!isset($_GET['token']) || empty($_GET['token'])) {
    die('Acceso no permitido');
}

$token = $_GET['token'];

// Buscar token válido
$sql = "SELECT pr.*, u.id AS usuario_id
        FROM password_resets pr
        INNER JOIN usuarios u ON pr.usuario_id = u.id
        WHERE pr.token = :token
        AND pr.expira_en >= NOW()
        AND pr.usado = 0
        LIMIT 1";

$stmt = $bd->prepare($sql);
$stmt->execute([':token' => $token]);
$reset = $stmt->fetch(PDO::FETCH_ASSOC);

// Token inválido o expirado
if (!$reset) {
    die('El enlace es inválido o ya expiró');
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $password  = trim($_POST['password'] ?? '');
    $password2 = trim($_POST['password2'] ?? '');

    // Validaciones
    if (empty($password) || empty($password2)) {
        $errores[] = 'Completa ambos campos';
    }

    if ($password !== $password2) {
        $errores[] = 'Las contraseñas no coinciden';
    }

    if (strlen($password) < 6) {
        $errores[] = 'La contraseña debe tener al menos 6 caracteres';
    }

    // Si todo está OK
    if (count($errores) === 0) {

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Actualizar contraseña en usuarios
        $stmt = $bd->prepare("UPDATE usuarios SET password = :password WHERE id = :id");
        $stmt->execute([
            ':password' => $passwordHash,
            ':id' => $reset['usuario_id']
        ]);

        // Marcar token como usado
        $stmt = $bd->prepare("UPDATE password_resets SET usado = 1 WHERE id = :id");
        $stmt->execute([
            ':id' => $reset['id']
        ]);

        // Redirigir
        header('Location: index.php?reset=ok');
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SUFI Perú</title>
    <meta name="Author" lang="es" content="Gean Huaman">
    <meta name="keywords" content="Gestor de pedidos">
    <meta name="description" content="decripcion" />
    <meta name="copyright" content="" />
    <meta name="robots" content="index, follow">
    <!--css-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="./src/css/style.css">
    <!--iconos inonify-->
    <script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
    <!--iconos boostrap-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>

    <section class="resetContainer">
        <section class="resetContaineInner ">
            <div><img src="./src/img/logo_admin/6_sinmargen_sf.png" alt=""></div>

            <h3>Restablecer contraseña</h23>

            <?php if ($errores): ?>
                <?php foreach ($errores as $error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endforeach; ?>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Nueva contraseña">
                </div>

                <div class="form-group mb-3">
                    <input type="password" name="password2" class="form-control" placeholder="Confirmar contraseña">
                </div>

                <button type="submit" class="btn btn-dark">Actualizar contraseña</button>
            </form>
        </section>
    </section>


    
</body>
</html>