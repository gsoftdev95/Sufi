    <?php
    require_once('./helpers/dd.php');
    require_once('./controllers/functions.php');
    require_once('./src/partials/conexionBD.php');

    $token = $_GET['token'] ?? '';

    if(empty($token)){
        die("Token no válido.");
    }

    $pedido = obtenerPedidoPorToken($bd, $token);

    if(!$pedido){
        die("Pedido no encontrado.");
    }

    $estado = $pedido['estado'] ?? '';

    $imagenesEstados = [
        'pendiente' => 'pedido_pendiente.svg',
        'confirmado' => 'pedido_confirmado.svg',
        'en_proceso' => 'pedido_enproceso.svg',
        'enviado' => 'pedido_enviado.svg',
        'entregado' => 'pedido_entregado.svg',
        'cancelado' => 'pedido_cancelado.svg'
    ];

    //fecha y hora
    $fechaCompleta = $pedido['fecha'];

    $fechaFormateada = '';
    $horaFormateada = '';

    if (!empty($fechaCompleta)) {
        $timestamp = strtotime($fechaCompleta);
        $fechaFormateada = date('d/m/Y', $timestamp);
        $horaFormateada = date('H:i', $timestamp);
    }





    ?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <?php include_once('./src/partials/head.php')?> 
        <link rel="stylesheet" href="./src/css/seguimiento.css">
    </head>
    <body>
        <section class="navSufi">
            <div class="logoPrincipal">
                <img src="./src/img/logo_admin/pequeña-sf.png" alt="">
            </div>
            <div class="Seguimiento_logoNeg">
                
                <?php if(!empty($pedido['logo'])): ?>
                    <img src="./src/img/logos/<?= htmlspecialchars(basename($pedido['logo'])) ?> ?>" 
                        alt="<?= htmlspecialchars($pedido['nombre_negocio']) ?>">
                <?php else: ?>
                    <span><?= htmlspecialchars($pedido['nombre_negocio']) ?></span>
                <?php endif; ?>
            </div>
        </section>
        <section class="containerSeguimiento">
            <div class="containerSeguimientoInner">
                <h1>Seguimiento de Pedido</h1>

                <div class="containerDetailsOrder">
                    <iconify-icon icon="lets-icons:check-fill" class="iconcheckSeguimiento"></iconify-icon>
                    <p><strong>Cliente:</strong> <?= htmlspecialchars($pedido['publico_final']) ?></p>
                </div>

                <div class="containerDetailsOrder">
                    <iconify-icon icon="lets-icons:check-fill" class="iconcheckSeguimiento"></iconify-icon>
                    <p><strong>Descripción:</strong> <?= htmlspecialchars($pedido['descripcion']) ?></p>
                </div>

                <div class="containerDetailsOrder">
                    <iconify-icon icon="lets-icons:check-fill" class="iconcheckSeguimiento"></iconify-icon>
                    <p><strong>Dirección de envío:</strong> <?= htmlspecialchars($pedido['direccion']) ?></p>
                </div>

                <div class="containerDetailsOrder">
                    <iconify-icon icon="lets-icons:check-fill" class="iconcheckSeguimiento"></iconify-icon>
                    <p><strong>Fecha de registro:</strong> <?= htmlspecialchars($fechaFormateada) ?></p>
                </div>

                <div class="containerDetailsOrder">
                    <iconify-icon icon="lets-icons:check-fill" class="iconcheckSeguimiento"></iconify-icon>
                    <p><strong>Hora de registro:</strong> <?= htmlspecialchars($horaFormateada) ?></p>
                </div>
                
                

                <div class="viewEstdClntFinal">
                    <p>Tu pedido se encuentra en estado <strong><?= htmlspecialchars($pedido['estado']) ?></strong> </p>

                    <?php if (isset($imagenesEstados[$estado])): ?>
                        <img src="./src/picture/<?= $imagenesEstados[$estado] ?>" 
                            alt="estado del pedido" 
                            class="imgEstadosUI">
                    <?php endif; ?>


                </div>
            </div>
        </section>
        
        <footer class="footerSufi">
            <p class="disclaimerSufi">
                SUFI es una plataforma digital de gestión y seguimiento de pedidos. 
                Cada emprendimiento es independiente y responsable de sus productos, servicios, entregas y atención al cliente. 
                SUFI no participa en transacciones comerciales ni garantiza el cumplimiento de obligaciones por parte de los negocios registrados.
            </p>
        </footer>
        

    </body>
    </html>