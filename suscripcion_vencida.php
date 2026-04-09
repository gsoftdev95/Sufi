<?php
require_once('./helpers/dd.php');
require_once('./controllers/functions.php');
require_once('./src/partials/conexionBD.php');

if (!isset($_SESSION['username'])) {
    header('Location: /Paginas_web/SUFI/gestor_v1/index.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include_once('./src/partials/head.php')?>
    <link rel="stylesheet" href="./src/css/style.css">
</head>
<body>
    
    <div class="suscripcionVencidaContainer">

        <div class="suscripcionCard">

            <div class="iconoAlerta">
                <i class="bi bi-exclamation-triangle"></i>
            </div>

            <h1>Suscripción vencida</h1>

            <p>
                Tu suscripción ha vencido y el acceso al sistema se encuentra temporalmente bloqueado.
            </p>

            <p>
                Para continuar utilizando la plataforma, por favor realiza la renovación de tu suscripción.
            </p>

            <div class="infoContacto">
                <p>Si ya realizaste el pago, comunícate con el administrador.</p>
            </div>

            <div class="accionesSuscripcion">
                <a href="/Paginas_web/SUFI/gestor_v1/logout.php" class="btnSalir">
                    Cerrar sesión
                </a>
            </div>

        </div>

    </div>

    
</body>
</html>
