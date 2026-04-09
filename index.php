<?php
require_once('./helpers/dd.php');
require_once('./controllers/functions.php');
require_once('./src/partials/conexionBD.php');

$errores = [];
$contraseña = "";

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $errores = validarUsernameLogin($_POST);

    if(count($errores) === 0){

        $usuario = buscarPorUsername($bd, $user);

        if($usuario === false){
            $errores['general'] = 'Usuario o contraseña inválidos';
        } else {

            // Verificar contraseña
            if(!password_verify($password, $usuario['password'])){
                $errores['general'] = 'Usuario o contraseña inválidos';
            // Verificar si la cuenta está activa
            } elseif($usuario['estado'] !== 'activo'){
                $errores['estado'] = 'Tu cuenta está inactiva o suspendida';
            } else {
                // Seguridad: limpiar sesión anterior
                session_unset();

                // Seguridad: regenerar ID de sesión
                session_regenerate_id(true);

                // Guardar datos del usuario en sesión
                seteoUsername($usuario);

                // 🚀 Redirección según rol
                if($usuario['rol'] === 'admin'){
                    header("Location: view/admin/dashboardAdmin.php");
                } else {
                    header("Location: view/cliente/dashboardCliente.php");
                }
                exit;
            }
        }
    }

    // Guardar errores en sesión (para mostrarlos después del redirect)
    $_SESSION['errores'] = $errores;

    // Redirigir para evitar reenvío del formulario (PRG pattern)
    header("Location: index.php");
    exit;
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma de gestor de pedidos</title>
    <link rel="stylesheet" href="./src/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="../../src/css/style.css">
</head>
<body>
    
    <section class="bodymainLogin">
        <div class="loginContainer">
            <div class="welcomeText">
                <span class="highlight"><img src="./src/img/logo_admin/6_sinmargen_sf.png" alt=""></span>
            </div>
            <p class="note">Bienvenidos, inicia sesión con tu cuenta para gestionar tus pedidos</p>
            
            <form action="" class="formLogin" method="POST">
                <?php
                    $errores = $_SESSION['errores'] ?? [];
                    unset($_SESSION['errores']);
                ?>

                <?php if(!empty($errores)): ?>
                    <ul class="m-0 px-2 text-danger list-unstyled">
                        <?php foreach($errores as $key => $error): ?>
                        <li class=errorLogin> <?= htmlspecialchars($error) ?> </li>
                        <?php endforeach ?>
                    </ul>
                <?php endif ?>

                <input type="user" name="username" placeholder="Ingrese su usuario" required>
                <input type="password" name="password" id="password" placeholder="Ingrese su contraseña">
                
                <div class="loginOptions">
                    <!--
                    <label class="rememberMe">
                        <input type="checkbox" name="recuerdame" id="recuerdame">
                        <label class="ms-1" for="recordarme">Recuérdame</label>
                    </label>
                        -->

                    <a href="#" class="forgotPassword">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
                <button type="submit" class="btnPyt btnAccepted btnLogin">Iniciar sesion</button>
            </form>

        </div>
    </section>
</body>
</html>
