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
                        <div class="cardTitleFnzs">Ingresos Reales (Entregados)</div>
                        <div class="cardValueFnzs">S/. <?= number_format($ingresosReales, 2) ?></div>
                    </div>

                    <div class="cardDashFnzs">
                        <div class="cardTitleFnzs">Ingresos Activos (Sin Cancelados)</div>
                        <div class="cardValueFnzs">S/. <?= number_format($ingresosActivos, 2) ?></div>
                    </div>

                    <div class="cardDashFnzs">
                        <div class="cardTitleFnzs">Ingresos Cancelados</div>
                        <div class="cardValueFnzs">S/. <?= number_format($ingresosCancelados, 2) ?></div>
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
