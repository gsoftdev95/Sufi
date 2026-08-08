<?php
require_once('../../helpers/dd.php');
require_once('../../controllers/functions.php');
require_once('../../src/partials/conexionBD.php');

controlAcceso($bd, ['cliente']);
$cliente_id = $_SESSION['cliente_id'];

$ingresosReales = obtenerIngresosReales($bd, $cliente_id);
$ingresosActivos = obtenerIngresosActivos($bd, $cliente_id);
$ingresosCancelados = obtenerIngresosCancelados($bd, $cliente_id);
$ingresosPorEstado = obtenerIngresosPorEstadoFinanciero($bd, $cliente_id);
$pedidosMes = obtenerCantidadPedidosMensual($bd, $cliente_id);
$pedidosEntregadosMes = obtenerCantidadPedidosEntregadosMensual($bd, $cliente_id);

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
                
                <h3 class="mt-3">Finanzas</h3>
                
                <section class="containerCardsFnzs">
                    <div class="cardDashFnzs">
                        <div class="cardTitleFnzs">Ingresos Reales</div>
                        <div class="cardDescriptionFnzs">Pedidos entregados y completados</div>
                        <div class="cardValueFnzs">S/. <?= number_format($ingresosReales, 2) ?></div>
                    </div>

                    <div class="cardDashFnzs">
                        <div class="cardTitleFnzs">Ventas en proceso</div>
                        <div class="cardDescriptionFnzs">Pedidos activos que aún no se cancelan</div>
                        <div class="cardValueFnzs">S/. <?= number_format($ingresosActivos, 2) ?></div>
                    </div>

                    <div class="cardDashFnzs">
                        <div class="cardTitleFnzs">Ventas Canceladas</div>
                        <div class="cardDescriptionFnzs">Pedidos que no se concretaron</div>
                        <div class="cardValueFnzs">S/. <?= number_format($ingresosCancelados, 2) ?></div>
                    </div>

                    <div class="cardDashFnzs">
                        <div class="cardTitleFnzs">Pedidos del mes</div>
                        <div class="cardDescriptionFnzs">Total de pedidos registrados este mes</div>
                        <div class="cardValueFnzs"><?= number_format($pedidosMes, 0) ?></div>
                    </div>

                    <div class="cardDashFnzs">
                        <div class="cardTitleFnzs">Pedidos entregados este mes</div>
                        <div class="cardDescriptionFnzs">Pedidos completados en el mes actual</div>
                        <div class="cardValueFnzs"><?= number_format($pedidosEntregadosMes, 0) ?></div>
                    </div>
                </section>

                <section class="containertableFinanzas">
                    <table class="table table-hover">
                        <thead>
                            <tr class="table-primary">
                                <th>Estado</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($ingresosPorEstado as $estado): ?>
                                <tr>
                                    <td><?= ucfirst($estado['nombre']) ?></td>
                                    <td>S/. <?= number_format($estado['total'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
            </div>
        </section>

        <footer>
            footer
        </footer>
        
    </section>

    
</body>
</html>
