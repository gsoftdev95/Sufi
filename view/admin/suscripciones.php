<?php
require_once('../../helpers/dd.php');
require_once('../../controllers/functions.php');
require_once('../../src/partials/conexionBD.php');

controlAcceso($bd, ['admin']);

actualizarEstadosVencidos($bd);

if(isset($_GET['busquedaSuscripcion']) && trim($_GET['busquedaSuscripcion']) != ''){
    $suscripcion = buscarCliente($bd, $_GET['busquedaSuscripcion'], $_GET['tipoBusquedaSuscripcion']);
}else{
    $suscripcion = listarCliente($bd);
}

//RENOVAR NEGOCIO
if(isset($_POST['renovar_negocio'])){
    $errores = [];
    $renovar = renovarNegocio($bd, $_POST);

    if ($renovar) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $errores[] = "Error al renovar el periodo del negocio.";
        }
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

                <h3 class="mt-3">Suscripciones</h3>

                <div class="containerTableSuscripciones">
                    <section class="containerFormSearSus">
                        <form class="suscripSearchForm mt-3 mb-4" role="search" action="#" method="GET">
                            <input class="form-control me-2" type="search" placeholder="Buscador..." aria-label="Search" name="busquedaSuscripcion">
                            <select name="tipoBusquedaSuscripcion" id="tipoBusquedaSuscripcion" class="border border-0 rounded-2 shadow-sm">
                                <option value="nombre_negocio">Por cliente</option>
                                <option value="representante">Por rrll</option>
                                <option value="fecha_inicio">Por mes</option>
                                <option value="fecha_inicio">Por año</option>
                            </select>
                            <button class="btn btnSearchFormSus" data-bs-toggle="collapse" data-bs-target="#verSuscripcion" aria-expanded="<?= $busquedaActivaSuscripciones? 'true' : 'false' ?>" aria-controls="verSuscripcion">Buscar</button>
                        </form>
                    </section>

                    <section class="contTableInner contTableInnerSuscrip">
                        <table class="table table-hover">
                            <thead>
                                <tr class="table-primary">
                                    <th>Id</th>
                                    <th>Negocio</th>
                                    <th>Fecha inicio</th>
                                    <th>Fecha fin</th>
                                    <th>Dias restantes</th>
                                    <th>Estado</th>
                                </tr>                            
                            </thead>
                            <tbody>
                                <?php foreach($suscripcion as $id => $suscripciones):?>
                                    <tr>
                                        <td><?= $suscripciones['id'] ?></td>
                                        <td><?= $suscripciones['nombre_negocio'] ?></td>
                                        <td><?= $suscripciones['fecha_inicio'] ?></td>
                                        <td><?= $suscripciones['fecha_fin'] ?></td>
                                        <td>
                                            <?php
                                                $hoy = new DateTime();
                                                $fechaFin = new DateTime($suscripciones['fecha_fin']);

                                                if ($fechaFin > $hoy) {
                                                    $diferencia = $hoy->diff($fechaFin);
                                                    $dias = $diferencia->days+1;

                                                    if ($dias <= 5) {
                                                        echo "<span style='color:orange;'>$dias días</span>";
                                                    } else {
                                                        echo "<span style='color:green;'>$dias días</span>";
                                                    }

                                                } else {
                                                    echo "<span style='color:red;'>Vencido</span>";
                                                }
                                            ?>
                                        </td>
                                        <td><?= $suscripciones['estado_pago'] ?></td>
                                    </tr>
                                <?php endforeach?>
                            </tbody>
                        </table>
                    </section>
                    
                </div>

            </div>
        </section>


        <!--Modal renovacion-->
        <div class="modalOverlayNegRenovacion" id="modalNegRenovacion">
            <div class="modalRenovacion">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Renovar periodo</h5>
                    <button type="button" class="btn-close closeModalNegocio"></button>
                </div>

                <form method="POST">

                    <!-- ID oculto -->
                    <input type="hidden" name="renovar_negocio" id="idNegocio">

                    <div class="mb-2">
                        <label class="form-label m-0">Nombre del negocio</label>
                        <input type="text" id="nombreNegocio" class="form-control m-0" readonly>
                    </div>

                    <div class="mb-2">
                        <label class="form-label m-0">Cantidad de meses</label>
                        <input type="number" id="cantidad" name="cantidad" min="1" max="24" step="1" value="1"class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label>Fecha inicio</label>
                        <input type="date" class="form-control mb-3" name="fecha_inicio" id="renFechaInicio">

                        <label>Fecha fin</label>
                        <input type="date" class="form-control mb-3" name="fecha_fin" id="renFechaFin">
                    </div>

                    <div class="mt-4 d-grid">
                        <button type="submit" class="btnRenovarnegocio">
                            Renovar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <footer>
            footer
        </footer>
        
    </section>

    <!--script modal renovar-->
    <script>
        const modal = document.getElementById("modalNegRenovacion");
        const botones = document.querySelectorAll(".openModalRenovar");
        const closeBtn = document.querySelector(".closeModalNegocio");

        botones.forEach(btn => {
            btn.addEventListener("click", () => {

                const id = btn.getAttribute("data-id");
                const nombre = btn.getAttribute("data-nombre");
                const fechaInicio = btn.dataset.fechaInicio;
                const fechaFin = btn.dataset.fechaFin;

                document.getElementById("idNegocio").value = id;
                document.getElementById("nombreNegocio").value = nombre;
                document.getElementById("renFechaInicio").value = fechaInicio;
                document.getElementById("renFechaFin").value = fechaFin;

                modal.style.display = "flex";
            });
        });

        closeBtn.addEventListener("click", () => {
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
