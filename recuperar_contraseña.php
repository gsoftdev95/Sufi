<?php
    require_once('./helpers/dd.php');
    require_once('./controllers/functions.php');
    require_once('./src/partials/conexionBD.php');
    $errores = [];
    $mensaje = "";

    if ($_POST) {
        $correo = $_POST['correo'];

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores['correo'] = 'Correo inválido';
        }

        if (empty($errores)) {

            $usuario = buscarUsuarioPorCorreo($bd, $correo);

            // Mensaje neutro (seguridad)
            $mensaje = "Si el usuario existe, te enviaremos un enlace de recuperación.";

            if ($usuario) {
                crearTokenRecuperacion($bd, $usuario);
            }
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
    
    <section class="recuperarContainer">
        <div class="recuperarInner">
            <div><img src="./src/img/logo_admin/6_sinmargen_sf.png" alt=""></div>
            <h3 class="mt-3"><i>¿Has olvidado tu contraseña?</i></h3>
            <p>Ingresa tu correo electronico y te enviaremos las instrucciones</p>

            <?php if ($mensaje): ?>
                <div class="mensaje-recuperacion">
                    <?= htmlspecialchars($mensaje) ?>
                </div>
            <?php endif; ?>

            <form method="post" action="">
                <input type="email" class="form-control mt-3" name="correo" placeholder="correo electronico" required>
                <button type="submit"  class="mt-3">Enviar enlace</button>
            </form>
        </div>        
    </section>
    
    
    <?php if ($mensaje): ?>
        <script>
            setTimeout(() => {
                window.location.href = "index.php";
            }, 20000); // 30 segundos
        </script>
    <?php endif; ?>


</body>
</html>