<?php
http_response_code(503); // Servicio no disponible
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>En mantenimiento</title>
    <link rel="stylesheet" href="./src/css/mantenimiento.css">
</head>
<body class="bg-light">

<div class="containerMantenimiento ">
    <div>
        <h1 class="titleMant" id="titulo"><i>PAGINA EN MANTENIMIENTO</i></h1>

        <p class="mensajeMantenimiento" id="mensaje">
            Estamos realizando mejoras para brindarte una mejor experiencia.           
            <br><br>
            Esta sección estará disponible muy pronto. Gracias por tu paciencia y comprensión.
        </p>
    </div>
    <picture><img src="./src/img/logo_admin/3.1_sf.png" alt="logo"></picture>
</div>



</body>
</html>
