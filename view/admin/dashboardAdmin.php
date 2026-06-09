<?php
require_once('../../helpers/dd.php');
require_once('../../controllers/functions.php');
require_once('../../src/partials/conexionBD.php');

controlAcceso($bd, ['admin']);

$totalClientes = obtenerTotalClientes($bd);
$clientesActivos = obtenerClientesActivos($bd);
$clientesInactivos = obtenerClientesInactivos($bd);
$clientesMorosos = obtenerClientesMorosos($bd);
$ingresosMes = obtenerIngresosMesAdmin($bd);

$ingresosPorMes = obtenerIngresosPorMesAdminGrafico($bd);
$nuevosClientesPorMes = obtenerClientesPorMesAdminGrafico($bd);

$clientesPorVencer = obtenerClientesPorVencer($bd);
?>


<!DOCTYPE html>
<html lang="en">
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
                
                <?php include_once('../../src/partials/menuAdmin.php')?>

            </div>
            <div class="bodyGestor">
                <?php include_once('../../src/partials/welcome.php')?>

                <h3 class="mt-3">Dashboard</h3>

                <section class="containerCardsDash">
                    <div class="cardDash">
                        <div class="cardTitle">Clientes Registrados</div>
                        <div class="cardValue"><?= $totalClientes ?></div>
                    </div>

                    <div class="cardDash">
                        <div class="cardTitle">Clientes Activos</div>
                        <div class="cardValue"><?= $clientesActivos ?></div>
                    </div>

                    <div class="cardDash">
                        <div class="cardTitle">Clientes Inactivos</div>
                        <div class="cardValue"><?= $clientesInactivos ?></div>
                    </div>

                    <div class="cardDash">
                        <div class="cardTitle">Clientes Morosos</div>
                        <div class="cardValue"><?= $clientesMorosos ?></div>
                    </div>

                    <div class="cardDash">
                        <div class="cardTitle">Ingresos del Mes</div>
                        <div class="cardValue">
                            S/. <?= number_format($ingresosMes,2) ?>
                        </div>
                    </div>

                    
                </section>

                <section class="sectionChartsDash">
                    <div class="chartBox">
                        <h5>Ingresos por Mes</h5>
                        <canvas id="chartIngresos"></canvas>
                    </div>

                    <div class="chartBox">
                        <h5>Nuevos Clientes por Mes</h5>
                        <canvas id="chartClientes"></canvas>
                    </div>
                </section>

                <div class="chartBox mt-4">

                    <h5>Clientes próximos a vencer</h5>

                    <table class="table">

                        <thead>
                            <tr>
                                <th>Negocio</th>
                                <th>Vence</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php foreach($clientesPorVencer as $cliente): ?>

                            <tr>
                                <td><?= htmlspecialchars($cliente['nombre_negocio']) ?></td>
                                <td><?= $cliente['fecha_fin'] ?></td>
                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>
            </div>
        </section>


        

        <footer>
            footer
        </footer>
        
    </section>

    
    <script>

    const ingresosPorMes = <?= json_encode($ingresosPorMes) ?>;
    const nuevosClientesPorMes = <?= json_encode($nuevosClientesPorMes) ?>;

    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../../src/js/dashboardAdmin.js"></script>
</body>
</html>
