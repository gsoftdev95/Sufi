<?php
require_once('../../helpers/dd.php');
require_once('../../controllers/functions.php');
require_once('../../src/partials/conexionBD.php');

controlAcceso($bd, ['admin']);

$totalAnualFacturaAdmin = obtenerTotalPagosAnual($bd);
$totalMesFacturaAdmin = obtenerTotalPagosMesActual($bd);
$totalMesAntFacturaAdmin = obtenerTotalPagosMesAnterior($bd);

// Obtener pagos con nombre del cliente
$sql = "SELECT 
            p.fecha_pago,
            c.nombre_negocio,
            p.meses_pagados,
            p.monto,
            p.metodo_pago
        FROM pagos p
        INNER JOIN clientes c ON p.cliente_id = c.id
        ORDER BY p.fecha_pago DESC";

$stmt = $bd->prepare($sql);
$stmt->execute();
$pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);


$sqlClientes = "SELECT id, nombre_negocio FROM clientes ORDER BY nombre_negocio ASC";
$stmtClientes = $bd->prepare($sqlClientes);
$stmtClientes->execute();
$clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);

// Procesar registro de pago
if(isset($_POST['registrar_pago'])){

    $cliente_id = $_POST['cliente_id'];
    $monto = $_POST['monto'];
    $meses = $_POST['meses'];
    $metodo = $_POST['metodo'];

    registrarPago($bd, $cliente_id, $monto, $meses, $metodo);

    header("Location: facturacion.php");
    exit;
}
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

                <h3 class="mt-3">Facturación</h3>

                <div class="containerCardsFacturacion">
                    <button class="cardFacturacion registrarPagoBtn" type="button">
                        <div class="titleCardsFact">Registrar pago</div>
                        <div class="rspCardFact"><span class="iconify-inline" data-icon="tabler:moneybag-plus"></span></div>                            
                    </button>
                    <div class="cardFacturacion">
                        <div class="titleCardsFact">Total anual</div>
                        <div class="rspCardFact"><?= number_format($totalAnualFacturaAdmin,2) ?></div>
                    </div>
                    <div class="cardFacturacion">
                        <div class="titleCardsFact">Total mensual</div>
                        <div class="rspCardFact"><?= number_format($totalMesFacturaAdmin,2) ?></div>
                    </div>
                    <div class="cardFacturacion">
                        <div class="titleCardsFact">Total mes anterior</div>
                        <div class="rspCardFact"><?= number_format($totalMesAntFacturaAdmin,2) ?></div>
                    </div>
                    
                </div>

                <div class="containerTableFact">
                    <div class="contTableInner contTableInnerFact">
                        <table class="table table-hover">
                            <thead>
                                <tr class="table-primary">
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Servicio/plan</th>
                                    <th>Monto</th>
                                    <th>Metodo</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($pagos)) : ?>
                                    <?php foreach ($pagos as $pago) : ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($pago['fecha_pago'])) ?></td>
                                            <td><?= htmlspecialchars($pago['nombre_negocio']) ?></td>
                                            <td><?= $pago['meses_pagados'] ?> mes(es)</td>
                                            <td><?= number_format($pago['monto'], 2) ?></td>
                                            <td><?= htmlspecialchars($pago['metodo_pago']) ?></td>
                                            <td>Pagado</td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6">No hay pagos registrados</td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                        </table>
                    </div>
                    
                </div>

            </div>
        </section>



        <!--Modal registro pago-->
        <section class="modalRegistroPago" id="idRegistroPago">
            <div class="modalcontentRegistroPago">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Registrar pago</h5>
                    <button type="button" class="btn-close closeModalRegPago"></button>
                </div>
                

                <form method="POST">
                    <div class="formGroup mb-2">
                        <select name="cliente_id" class="form-select"  required>
                            <option value="">Seleccione cliente</option>
                            <?php foreach($clientes as $c): ?>
                                <option value="<?= $c['id'] ?>">
                                    <?= htmlspecialchars($c['nombre_negocio']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="formGroup mb-2">
                        <input type="number" class="form-control" step="0.01" name="monto" placeholder="Monto S/." required>
                    </div>

                    <div class="formGroup mb-2">
                        <label>Meses pagados</label>
                        <input type="number" class="form-control" name="meses" value="1" min="1" placeholder="Meses pagados" required>
                    </div>

                    <div class="formGroup">
                        <input type="text" class="form-control" name="metodo" placeholder="Método de pago" required>
                    </div>

                    <div class="modalFooter">
                        <button type="submit" name="registrar_pago" class="btnGrdPago">
                            Guardar Pago
                        </button>
                    </div>
                </form>
            </div>
            
            
        </section>

        <footer>
            footer
        </footer>
        
    </section>


    <!--Script de registro pago-->
    <script>
        const modal = document.getElementById("idRegistroPago");
        const openBtn = document.querySelector(".registrarPagoBtn");
        const closeBtn = document.querySelector(".closeModalRegPago");

        openBtn.addEventListener("click", () =>{
            modal.style.display = "flex";
        });

        closeBtn.addEventListener("click", () =>{
            modal.style.display = "none";
        });
        window.addEventListener("click", (e) => {
            if(e.target === modal){
                modal.style.display = "none";
            }
        });
    </script>

    
</body>
</html>
