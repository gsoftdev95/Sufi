<?php
require_once('../../helpers/dd.php');
require_once('../../controllers/functions.php');
require_once('../../src/partials/conexionBD.php');

controlAcceso($bd, ['cliente']);



?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include_once('../../src/partials/head.php')?> 
</head>
<body>
    

    <section class="bodymainGestor">
        <section class="bannerPlataforma">
            <?php include_once('../../src/partials/header.php')?> 
        </section>

        <section class="bussinesSection">
            <div class="sidebarGestor">
                <?php include_once('../../src/partials/logoSidebar.php') ?>
                
                <?php include_once('../../src/partials/menuCliente.php')?>
            </div>

            <div class="bodyGestor">
                <?php include_once('../../src/partials/welcome.php')?>
                
                <h3 class="mt-3">Manual de uso</h3>
                
                <div class="containerManual">

                    <!-- INTRODUCCIÓN -->
                    <section class="manualSection">
                        <h5  class="TitleManual"><span class="iconify-inline iconHelloManual" data-icon="mdi:human-hello"></span> Bienvenido a SUFI</h5>
                        <p>
                            SUFI es tu gestor de pedidos diseñado para ayudarte a organizar tus ventas,
                            hacer seguimiento a tus clientes y llevar control de tu negocio de manera simple.
                        </p>
                    </section>

                    <!-- DEFINICIONES -->
                    <section class="manualSection">
                        <h5 class="subTitleManual">Definiciones de uso</h5>
                        <p>
                            Para efectos del presente sistema, se entenderá como <strong>EL PROVEEDOR</strong> a G-SOFT, empresa desarrolladora y propietaria de la plataforma SUFI; 
                            como <strong>EL CLIENTE</strong> al emprendedor o negocio que adquiere y utiliza el sistema para la gestión de sus pedidos; 
                            y como <strong>EL USUARIO FINAL</strong> a la persona que realiza pedidos a los productos o servicios ofrecidos por EL CLIENTE.
                        </p>
                    </section>

                    <!-- DASHBOARD -->
                    <section class="manualSection">
                        <h5 class="subTitleManual">¿Qué es el Dashboard?</h5>
                        <p>
                            El Dashboard te muestra un resumen general de tu negocio:
                        </p>
                        <ul>
                            <li>Pedidos totales registrados</li>
                            <li>Ingresos acumulados</li>
                            <li>Pedidos realizados este mes</li>
                            <li>Pedidos pendientes</li>
                        </ul>
                        <p>
                            También encontrarás gráficos que te ayudan a visualizar tus ventas por mes
                            y el estado actual de tus pedidos.
                        </p>
                    </section>

                    <!-- PEDIDOS -->
                    <section class="manualSection">
                        <h5  class="subTitleManual">Gestión de Pedidos</h5>
                        <p>
                            En la sección <strong>Pedidos</strong> puedes:
                        </p>
                        <ul>
                            <li>Registrar nuevos pedidos</li>
                            <li>Editar información de un pedido</li>
                            <li>Cambiar el estado (pendiente, enviado, entregado, etc.)</li>
                            <li>Eliminar pedidos</li>
                            <li>Generar un link de seguimiento para compartir con tu cliente</li>
                            <li>Exportar tus pedidos en formato CSV</li>
                        </ul>

                        <hr>

                        <!-- ESTADOS -->
                        <h6> Estados del Pedido</h6>
                        <p>
                            Cada pedido puede tener un estado diferente. Esto te ayuda a organizar el proceso:
                        </p>
                        <ul>
                            <li><strong>Pendiente:</strong> Pedido recién registrado.</li>
                            <li><strong>Confirmado:</strong> Pedido validado, se recomienda haber confirmado algún pago según su metodo de trabajo</li>
                            <li><strong>En proceso:</strong> Preparando el pedido.</li>
                            <li><strong>Enviado:</strong> Pedido en camino a la dirección de envío.</li>
                            <li><strong>Entregado:</strong> Pedido finalizado con éxito.</li>
                            <li><strong>Cancelado:</strong> Pedido anulado.</li>
                        </ul>

                        <hr>

                        <!-- LINK DE SEGUIMIENTO -->
                        <h6>Link de Seguimiento</h6>
                        <p>
                            Puedes generar un enlace único para cada pedido.
                            Este enlace permite que tu cliente consulte el estado de su pedido sin necesidad de iniciar sesión.
                        </p>
                        <p>
                            Solo debes hacer clic en el ícono de enlace dentro de la tabla de pedidos y compartirlo.
                        </p>
                    </section>

                    <!-- CONSEJOS -->
                    <section class="manualSection">
                        <h5  class="subTitleManual">Recomendaciones e indicaciones</h5>
                        <ul>
                            <li>Por seguridad, la sesión se cerrará automáticamente si hay más de 30 minutos de inactividad.</li>
                            <li>Mantén actualizados los estados de tus pedidos.</li>
                            <li>Registra todos tus pedidos para tener control real de tus ingresos.</li>
                            <li>Revisa el Dashboard con frecuencia para entender el crecimiento de tu negocio.</li>
                            <li>
                                Promueve siempre una atención honesta, transparente y responsable con tus clientes. 
                                G-SOFT, en su calidad de PROVEEDOR de la plataforma SUFI, no se hará responsable por prácticas indebidas, fraudes o incumplimientos del CLIENTE hacia sus USUARIOS FINALES. 
                                En caso de denuncias, reclamos formales o investigaciones por parte de autoridades competentes, el PROVEEDOR se verá en la obligación de proporcionar la información y datos necesarios que le sean requeridos conforme a la normativa vigente. 
                                Asimismo, ante evidencias de malas prácticas, el PROVEEDOR se reserva el derecho de suspender o finalizar el acceso del CLIENTE a la plataforma.
                            </li>
                        </ul>
                    </section>

                    <!-- FAQ -->
                    <section class="manualSection">
                        <h5  class="subTitleManual">Preguntas Frecuentes</h5>
                        <p><strong>¿Puedo editar un pedido después de crearlo?</strong><br>
                        Sí, siempre que no esté entregado o cancelado.</p>

                        <p><strong>¿Puedo eliminar un pedido?</strong><br>
                        Sí, pero esta acción no se puede deshacer.</p>

                        <p><strong>¿Los datos se guardan automáticamente?</strong><br>
                        Sí, una vez registrado el pedido queda almacenado en tu sistema.</p>
                    </section>

                </div>
            </div>
        </section>

        <footer>
            footer
        </footer>
        
    </section>

    
</body>
</html>
