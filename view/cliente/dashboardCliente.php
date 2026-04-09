<?php
require_once('../../helpers/dd.php');
require_once('../../controllers/functions.php');
require_once('../../src/partials/conexionBD.php');

controlAcceso($bd, ['cliente']);
$cliente_id = $_SESSION['cliente_id'];

$totalPedidos = obtenerTotalPedidos($bd, $cliente_id);
$ingresosTotales = obtenerIngresosTotales($bd, $cliente_id);
$pedidosMes = obtenerPedidosMesActual($bd, $cliente_id);
$pedidosPendientes = obtenerPedidosPendientes($bd, $cliente_id);
$ingresosPorMes = obtenerIngresosPorMes($bd, $cliente_id);
$pedidosPorMes = obtenerPedidosPorMes($bd, $cliente_id);
$pedidosPorEstado = obtenerPedidosPorEstado($bd, $cliente_id);



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
                
                <h3 class="mt-3">Dashboard</h3>

                <section class="containerCardsDash">

                    <div class="cardDash">
                        <div class="cardTitle">Pedidos Totales</div>
                        <div class="cardValue"><?= $totalPedidos ?></div>
                    </div>

                    <div class="cardDash">
                        <div class="cardTitle">Ingresos Totales</div>
                        <div class="cardValue">S/. <?= number_format($ingresosTotales, 2) ?></div>
                    </div>

                    <div class="cardDash">
                        <div class="cardTitle">Pedidos Este Mes</div>
                        <div class="cardValue"><?= $pedidosMes ?></div>
                    </div>

                    <div class="cardDash">
                        <div class="cardTitle">Pendientes</div>
                        <div class="cardValue"><?= $pedidosPendientes ?></div>
                    </div>

                </section>

                <section class="sectionChartsDash">

                    <div class="chartBox">
                        <h5>Ingresos por Mes</h5>
                        <canvas id="chartIngresos"></canvas>
                    </div>

                    <div class="chartBox">
                        <h5>Pedidos por Estado</h5>
                        <canvas id="chartEstados"></canvas>
                    </div>

                    <div class="chartBox">
                        <h5>Pedidos por Mes</h5>
                        <canvas id="chartPedidos"></canvas>
                    </div>

                </section>


            </div>
        </section>

        <footer>
            footer
        </footer>
        
    </section>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="../../src/js/main.js"></script>

    <script>
    const ingresosPorMes = <?= json_encode($ingresosPorMes) ?>;
    const pedidosPorMes = <?= json_encode($pedidosPorMes) ?>;
    const estadosLabels = <?= json_encode($pedidosPorEstado['labels']) ?>;
    const estadosData = <?= json_encode($pedidosPorEstado['data']) ?>;
    </script>

</body>
</html>
