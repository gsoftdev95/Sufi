<?php
require_once('../../helpers/dd.php');
require_once('../../controllers/functions.php');
require_once('../../src/partials/conexionBD.php');

controlAcceso($bd, ['admin']);


//modal creacion cliente
$errores = [];
$credenciales = null;
$exito = false;

if(isset($_POST['nombreNegocio'])) {
    $errores = validarCliente($_POST);
    $logo = armarLogo($_FILES);

    if(count($errores) === 0){

        $nuevoCliente = guardarCliente($bd, $_POST, $logo);

        if($nuevoCliente['exito']){
            $_SESSION['flash_exito'] = $nuevoCliente;
    
            header("Location: negocios.php");
            exit;
        } else {
            $errores[] = "Error al guardar el cliente.";
        }
    }
}

if(isset($_SESSION['flash_exito'])){
    $credenciales = $_SESSION['flash_exito'];
    $exito = true;
    unset($_SESSION['flash_exito']);
}


$totalClientes=contarClientes($bd);

if(isset($_GET['busquedaCliente']) && trim($_GET['busquedaCliente']) != '' ){
    $cliente = buscarCliente($bd, $_GET['busquedaCliente'], $_GET['tipoBusquedaCliente']);
} else {
    $cliente = listarCliente($bd);
}
$busquedaActivaClientes = isset($_GET['busquedaCliente']) && trim($_GET['busquedaCliente']) !== '';



//EDITAR NEGOCIO
if(isset($_POST['editar_negocio'])){

    $errores = [];

    if(empty($_POST['id'])){
        $errores[] = "ID inválido.";
    }

    if(empty($errores)){
        $updateNeg = modificarNegocio($bd, $_POST);

        if($updateNeg['exito']){
            header("Location: negocios.php");
            exit;
        } else {
            $errores[] = $updateNeg['error'];
        }
    }
}


// ELIMINAR NEGOCIO
if(isset($_POST['eliminar_negocio'])){

    $eliminado = eliminarNeg($bd, $_POST['id']);

    if($eliminado){
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    } else {
        $errores[] = "Error al eliminar el negocio.";
    }
}

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
                
                <?php include_once('../../src/partials/menuAdmin.php')?>
            </div>

            <div class="bodyGestor">
                <?php include_once('../../src/partials/welcome.php')?>
                
                <h3 class="mt-3">Negocios</h3>

                <div class="containerCardsNegocio">
                    <div class="cardNegocio">
                        <div class="titleCardNegocio">Clientes:</div>
                        <div class="rspCard"><?= $totalClientes ?></div>
                    </div>
                    <div class="cardNegocio crearClienteBtn">
                        <div class="titleCardNegocio">Crear cliente</div>
                        <div class="rspCard"><span class="iconify-inline iconCardNegocio" data-icon="ri:user-add-line"></span></div>
                    </div>
                </div>
                
                <section class="containerTableNegocio <?= $busquedaActivaClientes ? 'show' : '' ?> showSelectAdmin">
                    <section class="containerFormSearNeg">
                        <form class="negociosSearchForm mt-3 mb-4" role="search" action="#" method="GET">
                            <input class="form-control me-2" type="search" placeholder="Buscador..." aria-label="Search" name="busquedaCliente">
                            <select name="tipoBusquedaCliente" id="tipoBusquedaCliente" class="border border-0 rounded-2 shadow-sm">
                                <option value="nombre_negocio">Por cliente</option>
                                <option value="representante">Por rrll</option>
                                <option value="fecha_inicio">Por fecha</option>
                            </select>
                            <button class="btn btnSearchFormNeg" data-bs-toggle="collapse" data-bs-target="#verUsuarios" aria-expanded="<?= $busquedaActivaClientes? 'true' : 'false' ?>" aria-controls="verClientes">Buscar</button>
                        </form>
                    </section>

                    <section class="contTableInner contTableInnerNeg">
                        <table class="table table-hover">
                            <thead>
                                <tr class="table-primary">
                                    <th>Id</th>
                                    <th>Cliente</th>
                                    <th>rrll</th>
                                    <th>Celular</th>
                                    <th>Correo</th>
                                    <th>Logo</th>
                                    <th>Fecha registro</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($cliente as $clientes) :?>
                                    <tr>
                                        <td><?=  $clientes['id'] ?></td>
                                        <td><?=  $clientes['nombre_negocio'] ?></td>
                                        <td><?=  $clientes['representante'] ?></td>
                                        <td><?=  $clientes['celular'] ?></td>
                                        <td><?=  $clientes['correo'] ?></td>
                                        <td>
                                            <?php if($clientes['logo']): ?>
                                                <img src="../../<?= $clientes['logo'] ?>" width="40">
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $clientes['creado_en'] ?></td>
                                        <td><?=  $clientes['estado_pago'] ?></td>
                                        <td class="contAccionNegocios">
                                            <button class="openModalViewNeg"
                                                data-id="<?= htmlspecialchars($clientes['id']) ?>"
                                                data-nombre-neg="<?= htmlspecialchars($clientes['nombre_negocio']) ?>"
                                                data-representante-neg="<?= htmlspecialchars($clientes['representante']) ?>"
                                                data-celular="<?= htmlspecialchars($clientes['celular']) ?>"
                                                data-correo="<?= htmlspecialchars($clientes['correo']) ?>"
                                                data-creacion="<?= htmlspecialchars($clientes['creado_en']) ?>"
                                                data-estado-pago="<?= htmlspecialchars($clientes['estado_pago']) ?>"
                                                data-fecha-inicio="<?= htmlspecialchars($clientes['fecha_inicio']) ?>"
                                                data-fecha-fin="<?= htmlspecialchars($clientes['fecha_fin']) ?>"
                                            >
                                                <span class="iconify-inline iconTabNeg" data-icon="lets-icons:view-light"></span>
                                            </button>
                                            
                                            <button class="openModalEditNeg"
                                                    data-id="<?= $clientes['id'] ?>"
                                                    data-nombre-neg="<?= htmlspecialchars($clientes['nombre_negocio']) ?>"
                                                    data-representante-neg="<?= htmlspecialchars($clientes['representante']) ?>"
                                                    data-celular="<?= htmlspecialchars($clientes['celular']) ?>"
                                                    data-correo="<?= htmlspecialchars($clientes['correo']) ?>"
                                                    data-fecha-inicio="<?= htmlspecialchars($clientes['fecha_inicio']) ?>"
                                                    data-fecha-fin="<?= htmlspecialchars($clientes['fecha_fin']) ?>"
                                            >
                                                <span class="iconify-inline iconTabNeg" data-icon="mdi-light:pencil"></span>
                                            </button>
                                            
                                            <button class="openModalDeleteNeg"
                                                    data-id="<?= $clientes['id'] ?>"
                                                    data-nombre="<?= htmlspecialchars($clientes['nombre_negocio']) ?>">
                                                <span class="iconify-inline iconTabNeg" data-icon="solar:trash-bin-2-linear"></span>
                                            </button>                                      
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </section>
                    
                </section>
            </div>
        </section>



        <!-- Modal Crear Cliente -->
        <section class="modalOverlay" id="modalCliente">
            <div class="modalContent">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Crear Cliente</h5>
                    <button type="button" class="btn-close closeModal"></button>
                </div>

                <?php if (count($errores) > 0) : ?>
                    <ul class="alert alert-danger">
                        <?php foreach ($errores as $error) : ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <?php if ($exito): ?>
                    <div class="alert alert-success text-center">
                        Cliente creado correctamente.<br><br>
                        <strong>Usuario:</strong> <?= $credenciales['username'] ?><br>
                        <strong>Contraseña:</strong> <?= $credenciales['password'] ?>
                    </div>

                <?php endif; ?>


                <form method="POST" enctype="multipart/form-data">

                    <div class="mb-2">
                        <label class="form-label m-0">Nombre del negocio</label>
                        <input type="text" name="nombreNegocio" class="form-control m-0" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label m-0">Representante</label>
                        <input type="text" name="representante" class="form-control m-0" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label m-0">Celular</label>
                        <input type="text" name="celular" class="form-control m-0">
                    </div>

                    <div class="mb-2">
                        <label class="form-label m-0">Correo</label>
                        <input type="email" name="correo" class="form-control m-0" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label m-0">Logo de negocio</label>
                        <input type="file" id="imagen" name="logo" class="form-control m-0"  accept="image/*" >
                    </div>

                    <div class="row mb-2">
                        <div class="col ">
                            <label class="form-label m-0">Fecha inicio</label>
                            <input type="date" class="form-control m-0" name="fecha_inicio">
                        </div>

                        <div class="col">
                            <label class="form-label m-0">Fecha fin</label>
                            <input type="date" class="form-control m-0" name="fecha_fin">
                        </div>
                    </div>

                    <div class="mb-2">
                        <input type="number" name="monto_mensualidad" class="form-control" placeholder="S/." required>
                        <select name="metodo_pago" class="form-select  w-50" required>
                            <option value="efectivo">Efectivo</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="yape">Yape/Plin</option>
                        </select>
                    </div>

                    <div class="mt-4 d-grid">
                        <button type="submit" class="btnCrearCliente">
                            Crear Cliente
                        </button>
                    </div>

                </form>
            </div>
        </section>

        <!-- Modal ver Cliente -->
        <section class="modalViewNegocio" id="modalViewNegocio">
            <div class="modalcontentViewNeg">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">DATOS DEL NEGOCIO</h5>
                    <button type="button" class="btn-close closeModalViewNeg"></button>
                </div>
                
                <div class="mb-2">
                    <h6>Id del Negocio</h6>
                    <p id="viewId"></p>
                </div>
                <hr>

                <div class="mb-2">
                    <h6>Nombre del Negocio</h6>
                    <p id="viewNombreNeg"></p>
                </div>
                <hr>

                <div class="mb-2">
                    <h6>Representante legal</h6>
                    <p id="viewRepresentanteNeg"></p>
                </div>
                <hr>

                <div class="mb-2">
                    <h6>Celular</h6>
                    <p id="viewCelular"></p>
                </div>
                <hr>

                <div class="mb-2">
                    <h6>Correo</h6>
                    <p id="viewCorreo"></p>
                </div>
                <hr>

                <div class="mb-2">
                    <h6>Fecha de registro</h6>
                    <p id="viewFechaCreacion"></p>
                </div>
                <hr>

                <div class="mb-2">
                    <h6>Estado de pago</h6>
                    <p id="viewEstadoPago"></p>
                </div>
                <hr>
                
                <div class="mb-2">
                    <h6>Periodo habilitado</h6>
                    <p>
                        Desde: <span id="viewFechaInicio"></span><br>
                        Hasta: <span id="viewFechaFin"></span>
                    </p>
                </div>
                <hr>

            </div>
        </section>

        <!-- Modal editar Cliente -->
        <section class="modalEditNeg" id="modalEditNeg">
            <div class="modalContentEditNeg">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">EDITAR NEGOCIO</h5>
                    <button type="button" class="btn-close closeModalEditNeg"></button>
                </div>

                <form method="POST">
                    <input type="hidden" name="editar_negocio" value="1">

                    <input type="hidden" name="id" id="editId">

                    <label>Nombre del negocio</label>
                    <input type="text" class="form-control mb-3" name="nombre_negocio" id="editNombreNeg">

                    <label>Representante legal</label>
                    <input type="text" class="form-control mb-3" name="representante" id="editRepresentanteNeg">
                    
                    <label>Celular</label>
                    <input type="text" class="form-control mb-3" name="celular" id="editCelular">

                    <label>Correo</label>
                    <input type="email" class="form-control mb-3" name="correo" id="editCorreo">

                    <label>Fecha inicio</label>
                    <input type="date" class="form-control mb-3" name="fecha_inicio" id="editFechaInicio">

                    <label>Fecha fin</label>
                    <input type="date" class="form-control mb-3" name="fecha_fin" id="editFechaFin">

                    <button type="submit" class="btnUpNegocio">Guardar Cambios</button>

                </form>
            </div>        
        </section>

        <!-- Modal eliminar negocio -->
        <div class="modalDeleteNeg" id="modalDeleteNeg">
            <div class="modalContentDeleteNeg">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 titleModal">ELIMINAR NEGOCIO</h5>
                    <button type="button" class="btn-close closeModalDeleteNeg"></button>
                </div>

                <p> 
                    <span class="iconify-inline iconTabNeg iconAlert" data-icon="mingcute:alert-fill"></span>
                    ¿Estás seguro que quieres eliminar este negocio 
                    <strong id="deleteCliente"></strong>?
                    <span class="iconify-inline iconTabNeg iconAlert" data-icon="mingcute:alert-fill"></span>
                </p>

                <form method="POST">
                    <input type="hidden" name="eliminar_negocio" value="1">
                    <input type="hidden" name="id" id="deleteId">

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btnEliminar">
                            Eliminar
                        </button>

                        <button type="button" class="btn closeModalDeletePed">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        



        <footer>
            footer
        </footer>
        
    </section>

    <!--script Modal creacion negocio-->
    <script>
        const modal = document.getElementById("modalCliente");
        const openBtn = document.querySelector(".crearClienteBtn");
        const closeBtn = document.querySelector(".closeModal");

        openBtn.addEventListener("click", () => {
            modal.style.display = "flex";
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

    <?php if($exito || count($errores) > 0): ?>
    <script>
        document.getElementById("modalCliente").style.display = "flex";
    </script>
    <?php endif; ?>

    <!-- script Modal ver negocio-->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("modalViewNegocio");
            const openBtn = document.querySelectorAll(".openModalViewNeg");
            const closeBtn = document.querySelector(".closeModalViewNeg");

            openBtn.forEach(btn => {
                btn.addEventListener("click", () => {
                    document.getElementById("viewId").textContent = btn.dataset.id;
                    document.getElementById("viewNombreNeg").textContent = btn.dataset.nombreNeg;
                    document.getElementById("viewRepresentanteNeg").textContent = btn.dataset.representanteNeg;
                    document.getElementById("viewCelular").textContent = btn.dataset.celular;
                    document.getElementById("viewCorreo").textContent = btn.dataset.correo;
                    document.getElementById("viewFechaCreacion").textContent = btn.dataset.creacion;
                    document.getElementById("viewEstadoPago").textContent = btn.dataset.estadoPago;
                    document.getElementById("viewFechaInicio").textContent = btn.dataset.fechaInicio;
                    document.getElementById("viewFechaFin").textContent = btn.dataset.fechaFin;

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
        })        
    </script>

    <!-- script Modal editar negocio-->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("modalEditNeg");
            const openBtn = document.querySelectorAll(".openModalEditNeg");
            const closeBtn = document.querySelector(".closeModalEditNeg");

            openBtn.forEach(btn => {
                btn.addEventListener("click", () =>{

                    document.getElementById("editId").value = btn.dataset.id;
                    document.getElementById("editNombreNeg").value = btn.dataset.nombreNeg;
                    document.getElementById("editRepresentanteNeg").value = btn.dataset.representanteNeg;
                    document.getElementById("editCelular").value = btn.dataset.celular;
                    document.getElementById("editCorreo").value = btn.dataset.correo;
                    document.getElementById("editFechaInicio").value = btn.dataset.fechaInicio;
                    document.getElementById("editFechaFin").value = btn.dataset.fechaFin;

                    modal.style.display = "flex";                    
                })
            });
            

            if (closeBtn) {
                closeBtn.addEventListener("click", () => {
                    modal.style.display = "none";
                });
            }

            window.addEventListener("click", (e) => {
                if(e.target === modal){
                    modal.style.display = "none";
                }
            });
        })
    </script>

    <!-- script para modal eliminar enogico-->
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            const modal = document.getElementById("modalDeleteNeg");
            const botones = document.querySelectorAll(".openModalDeleteNeg");
            const closeBtn = document.querySelector(".closeModalDeleteNeg");
            const cancelBtn = document.querySelector(".closeModalDeletePed");

            botones.forEach(btn => {
                btn.addEventListener("click", () => {

                    const id = btn.dataset.id;
                    const nombre = btn.dataset.nombre;

                    document.getElementById("deleteId").value = id;
                    document.getElementById("deleteCliente").textContent = nombre;

                    modal.style.display = "flex";
                });
            });

            if(closeBtn){
                closeBtn.addEventListener("click", () => {
                    modal.style.display = "none";
                });
            }

            if(cancelBtn){
                cancelBtn.addEventListener("click", () => {
                    modal.style.display = "none";
                });
            }

            window.addEventListener("click", (e) => {
                if (e.target === modal) {
                    modal.style.display = "none";
                }
            });

        });
    </script>
    
</body>
</html>
