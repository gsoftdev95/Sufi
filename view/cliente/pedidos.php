<?php
require_once('../../helpers/dd.php');
require_once('../../controllers/functions.php');
require_once('../../src/partials/conexionBD.php');

controlAcceso($bd, ['cliente']);
$cliente_id = $_SESSION['cliente_id'];

// EXPORTAR CSV
if (isset($_GET['exportar']) && $_GET['exportar'] === 'csv') {

    // Traemos los pedidos del cliente
    $pedidosExport = listarPedidos($bd, $cliente_id);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=pedidos_' . date('Y-m-d') . '.csv');

    $output = fopen('php://output', 'w');

    // Encabezados del CSV
    fputcsv($output, [
        'Cliente',
        'Descripcion',
        'Direccion',
        'Fecha',
        'Precio',
        'Cantidad',
        'Total',
        'Estado'
    ]);

    foreach ($pedidosExport as $ped) {
        fputcsv($output, [
            $ped['publico_final'],
            $ped['descripcion'],
            $ped['direccion'],
            $ped['fecha'],
            $ped['precio'],
            $ped['cantidad'],
            $ped['total'],
            $ped['estado_nombre']
        ]);
    }

    fclose($output);
    exit;
}

// BUSQUEDA Y LISTADO DE PEDIDOS
if(isset($_GET['busquedaPedidos']) && trim($_GET['busquedaPedidos']) !== ''){

    $busqueda = trim($_GET['busquedaPedidos']);
    $tipoBusqueda = $_GET['tipoBusquedaPedidos'] ?? 'publico_final';

    $pedido = buscarPedidos($bd, $busqueda, $tipoBusqueda, $cliente_id);

}else{
    $pedido = listarPedidos($bd, $cliente_id);
}


$errores = [];
$exito = false;

// CAMBIAR ESTADO
if(isset($_POST['cambiar_estado'])){
    cambiarEstadoPedido(
        $bd,
        $_POST['pedido_id'],
        $_POST['nuevo_estado'],
        $cliente_id
    );

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}


// CREAR PEDIDO
if(isset($_POST['crear_pedido'])) {

    $errores = validarPedido($_POST);

    if (empty($errores)) {
        $guardado = guardarPedido($bd, $_POST);

        if ($guardado) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $errores[] = "Error al guardar el pedido.";
        }
    }
}


//EDITAR PEDIDO
if(isset($_POST['editar_pedido'])){

    $errores = validarPedido($_POST);

    if (empty($errores)) {

        $actualizado = actualizarPedido($bd, $_POST, $cliente_id);

        if ($actualizado) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $errores[] = "Error al actualizar el pedido.";
        }
    }
}


// ELIMINAR PEDIDO
if(isset($_POST['eliminar_pedido'])){

    $eliminado = eliminarPedido($bd, $_POST['id'], $cliente_id);

    if($eliminado){
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    } else {
        $errores[] = "Error al eliminar el pedido.";
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
                
                <?php include_once('../../src/partials/menuCliente.php')?>
            </div>

            <div class="bodyGestor">
                <?php include_once('../../src/partials/welcome.php')?>
                
                <h3 class="mt-3">Pedidos</h3>
                
                <div class="containerCardsPedidos">
                    <button class="cardsPedidos openModalPedido addPedidoBtn">
                        <div class="titleCardPedidos ">Agregar pedido:</div>
                        <div class="rspCardPedido"><span class="iconify-inline" data-icon="lsicon:order-edit-outline"></div>
                    </button>
                    <!--
                    <div class="cardsPedidos">
                        <div class="titleCardPedidos">Agregar Cliente favorito:</div>
                        <div class="rspCardPedido"><span class="iconify-inline" data-icon="fluent:person-star-20-filled"></div>
                    </div>
                    -->
                </div>

                <section class="containerTablePedidos">
                    <section class="containerFormSearPed">
                        <form class="pedidosSearchForm mt-3 mb-4" role="search" action="#" method="GET">
                            <input class="form-control me-2" type="search" placeholder="Buscador..." aria-label="Search" name="busquedaPedidos">
                            <select name="tipoBusquedaPedidos" id="tipoBusquedaPedidos">
                                <option value="publico_final">Por cliente</option>
                                <option value="descripcion">Por descripcion</option>
                                <option value="precio">Por precio</option>
                                <option value="cantidad">Por cantidad</option>
                                <option value="fecha">Por fecha</option>
                            </select>
                            <button class="btn btnSearchFormPed" data-bs-toggle="collapse" data-bs-target="#verUsuarios" aria-expanded="<?= $busquedaActivaClientes? 'true' : 'false' ?>" aria-controls="verClientes">Buscar</button>
                        </form>
                        <div class="contDocsPed">
                            <a href="?exportar=csv" class="ExpCsvPed">Exportar CSV</a>
                        </div>
                    </section>

                    <section class="contTableInner contaTablePed">
                        <table class="table table-hover tablaPedidos" id="tablaPedidos">
                            <thead>
                                <tr class="table-primary">
                                    <th>Id</th>
                                    <th>Cliente</th>
                                    <th>Descripcion</th>
                                    <th>Dirección de envío</th>
                                    <th>Fecha</th>
                                    <th>Precio</th>                                
                                    <th>cantidad</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($pedido as $id =>$pedidos) :?>
                                <tr>
                                    <td>CDP-<?=  $pedidos['id'] ?></td>
                                    <td><?=  $pedidos['publico_final'] ?></td>
                                    <td title="<?= htmlspecialchars($pedidos['descripcion']) ?>" data-bs-toggle="tooltip"><?=  $pedidos['descripcion'] ?></td>
                                    <td title="<?= htmlspecialchars($pedidos['direccion']) ?>" data-bs-toggle="tooltip"><?=  $pedidos['direccion'] ?></td>
                                    <td><?=  $pedidos['fecha'] ?></td>
                                    <td><?=  $pedidos['precio'] ?></td>
                                    <td><?=  $pedidos['cantidad'] ?></td>
                                    <td><?=  $pedidos['total'] ?></td>
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" name="pedido_id" value="<?= $pedidos['id'] ?>">

                                            <?php
                                                $estadoActual = $pedidos['estado_id'];
                                                $opciones = obtenerOpcionesEstado($estadoActual);
                                                $estadosDisponibles = obtenerEstadosPorIds($bd, $opciones);
                                                // Si solo hay un estado posible, no mostramos select
                                                $soloEstadoActual = count($opciones) === 1;
                                            ?>

                                            <?php if($soloEstadoActual): ?>
                                                <span class="estado estado-<?= strtolower(trim($pedidos['estado_nombre'])) ?>">
                                                    <?= ucwords(str_replace('_', ' ', $pedidos['estado_nombre'])) ?>
                                                </span>
                                            <?php else: ?>
                                                <select name="nuevo_estado" class="estado estado-<?= strtolower(trim($pedidos['estado_nombre'])) ?>">
                                                    <?php foreach($estadosDisponibles as $estado): ?>
                                                        <option value="<?= $estado['id'] ?>" <?= ($estado['id'] == $estadoActual) ? 'selected' : '' ?> >
                                                            <?= ucfirst($estado['nombre']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button type="submit" name="cambiar_estado" class="checkEstado">
                                                    <span class="iconify-inline" data-icon="el:ok-sign">
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    </td>

                                    <td class="contAccionNegocios">
                                        <button class="openModalViewPedido"
                                            data-cliente="<?= htmlspecialchars($pedidos['publico_final']) ?>"
                                            data-descripcion="<?= htmlspecialchars($pedidos['descripcion']) ?>"
                                            data-direccion="<?= htmlspecialchars($pedidos['direccion']) ?>"
                                            data-fecha="<?= $pedidos['fecha'] ?>"
                                            data-precio="<?= $pedidos['precio'] ?>"
                                            data-cantidad="<?= $pedidos['cantidad'] ?>"
                                            data-total="<?= $pedidos['total'] ?>"
                                            data-estado="<?= htmlspecialchars($pedidos['estado_nombre']) ?>"
                                        >
                                            <span class="iconify-inline iconTabNeg" data-icon="lets-icons:view-light"></span>
                                        </button>

                                        <?php if($pedidos['estado_id'] != 5 && $pedidos['estado_id'] != 6): ?>
                                        <button class="openModalEditPedido"
                                            data-id="<?= $pedidos['id'] ?>"
                                            data-cliente="<?= htmlspecialchars($pedidos['publico_final']) ?>"
                                            data-descripcion="<?= htmlspecialchars($pedidos['descripcion']) ?>"
                                            data-direccion="<?= htmlspecialchars($pedidos['direccion']) ?>"
                                            data-precio="<?= $pedidos['precio'] ?>"
                                            data-cantidad="<?= $pedidos['cantidad'] ?>"
                                            data-estado="<?= $pedidos['estado_id'] ?>"
                                        >
                                            <span class="iconify-inline iconTabNeg" data-icon="mdi-light:pencil"></span>
                                        </button>
                                        <?php endif; ?>

                                        <button class="openModalDeletePedido"
                                            data-id="<?= $pedidos['id'] ?>"
                                            data-cliente="<?= htmlspecialchars($pedidos['publico_final']) ?>"
                                            data-descripcion="<?= htmlspecialchars($pedidos['descripcion']) ?>"
                                            data-total="<?= $pedidos['total'] ?>"
                                        >
                                            <span class="iconify-inline iconTabNeg" data-icon="solar:trash-bin-2-linear"></span>
                                        </button>

                                        <button class="openModalLinkPedido"
                                            data-token="<?= htmlspecialchars($pedidos['token_publico']) ?>">
                                            <span class="iconify-inline iconTabNeg" data-icon="line-md:link"></span>
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

        <!-- Modal agregar pedido -->
        <div class="modalOverlayPed" id="modalAddPedidos">
            <div class="modalContentPed">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 titleModal">CREAR PEDIDO</h5>
                    <button type="button" class="btn-close closeModalPed"></button>
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
                        Pedido registrado correctamente.<br><br>
                    </div>
                    <?php $exito = false; // Reinicia la variable ?>
                <?php endif; ?>


                <form method="POST">
                    <input type="hidden" name="crear_pedido" value="1">

                    <div class="mb-2">
                        <label class="form-label m-0">Nombre del cliente</label>
                        <input type="text" name="publico_final" class="form-control m-0" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label m-0">Descripción</label>
                        <input type="text" name="descripcion" class="form-control m-0" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label m-0">Dirección</label>
                        <input type="text" name="direccion" class="form-control m-0">
                    </div>

                    <div class="mb-2">
                        <label class="form-label m-0">Precio</label>
                        <input type="number" step="0.01" min="0" name="precio" id="precio" class="form-control m-0" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label m-0">Cantidad</label>
                        <input type="number" min="1" value="1" name="cantidad" id="cantidad" class="form-control m-0" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label m-0">Total</label>
                        <input type="number" step="0.01" name="total" id="total" class="form-control m-0" readonly>
                    </div>
                    
                    <div class="mt-4 d-grid">
                        <button type="submit" class="btnAddPedido">
                            Registrar pedido
                        </button>
                    </div>

                </form>

            </div>
        </div>

        <!-- Modal ver pedido -->
        <div class="modalViewPedidos" id="idmodalViewPedidos">
            <div class="modalContentviewPed">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 titleModal">VER PEDIDO</h5>
                    <button type="button" class="btn-close closeModalViewPed"></button>
                </div>

                <div class="mb-2">
                    <h6>Nombre del cliente</h6>
                    <p id="viewCliente"></p>
                </div>
                <hr>

                <div class="mb-2">
                    <h6>Descripción</h6>
                    <p id="viewDescripcion"></p>
                </div>
                <hr>

                <div class="mb-2">
                    <h6>Dirección</h6>
                    <p id="viewDireccion"></p>
                </div>
                <hr>

                <div class="mb-2">
                    <h6>Fecha</h6>
                    <p id="viewFecha"></p>
                </div>
                <hr>

                <div class="mb-2">
                    <h6>Precio</h6>
                    <p id="viewPrecio"></p>
                </div>
                <hr>

                <div class="mb-2">
                    <h6>Cantidad</h6>
                    <p id="viewCantidad"></p>
                </div>
                <hr>

                <div class="mb-2">
                    <h6>Total</h6>
                    <p id="viewTotal"></p>
                </div>
                <hr>

                <div class="mb-2">
                    <h6>Estado</h6>
                    <p id="viewEstado"></p>
                </div>

            </div>
        </div>

        <!-- Modal editar pedido -->
        <div class="modalEditPedidos" id="idmodalEditPedidos">
            <div class="modalContentEditPed">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 titleModal">EDITAR PEDIDO</h5>
                    <button type="button" class="btn-close closeModalEditPed"></button>
                </div>

                <form action="" method="POST">
                    <input type="hidden" name="editar_pedido" value="1">

                    <input type="hidden" name="id" id="editId">

                    <label>Cliente</label>
                    <input type="text" class="form-control mb-3" name="publico_final" id="editCliente">

                    <label>Descripción</label>
                    <input type="text" class="form-control mb-3" name="descripcion" id="editDescripcion">

                    <label>Dirección</label>
                    <input type="text" class="form-control mb-3" name="direccion" id="editDireccion">

                    <label>Precio</label>
                    <input type="number" class="form-control mb-3" name="precio" id="editPrecio">

                    <label>Cantidad</label>
                    <input type="number" class="form-control mb-3" name="cantidad" id="editCantidad">

                    <button type="submit" class="btnUpdPedido">Guardar Cambios</button>

                </form>
            </div>
        </div>

        <!-- Modal eliminar pedido -->
        <div class="modalDeletePedidos" id="idmodalDeletePedidos">
            <div class="modalContentDeletePed">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 titleModal">ELIMINAR PEDIDO</h5>
                    <button type="button" class="btn-close closeModalDeletePed"></button>
                </div>

                <p> 
                    <span class="iconify-inline iconTabNeg iconAlert" data-icon="mingcute:alert-fill"></span>
                    ¿Estás seguro que quieres eliminar el pedido del cliente 
                    <strong id="deleteCliente"></strong>?
                    <span class="iconify-inline iconTabNeg iconAlert" data-icon="mingcute:alert-fill"></span>
                </p>

                <p><strong>Descripción:</strong> <span id="deleteDescripcion"></span></p>
                <p><strong>Total:</strong> $<span id="deleteTotal"></span></p>

                <form method="POST">
                    <input type="hidden" name="eliminar_pedido" value="1">
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

        <!-- Modal link pedido -->
        <div class="modalLinkPedido" id="modalLinkPedido">
            <div class="modalContentLinkPed">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 titleModal">LINK DE SEGUIMIENTO</h5>
                    <button type="button" class="btn-close closeModalLinkPed"></button>
                </div>

                <p>Comparte este enlace con tu cliente:</p>
                <div class="d-flex">
                    <input type="text" id="inputLinkPedido" class="form-control mb-3" readonly>

                    <button class="btnCopyLink" id="copiarLinkPedido">
                        <iconify-icon icon="mingcute:copy-fill"></iconify-icon>
                    </button>
                </div>
                

            </div>
        </div>




        <footer>
            footer
        </footer>
        
    </section>
    



    <!-- script para modal agregar pedido -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("modalAddPedidos");
            const openBtn = document.querySelector(".openModalPedido");
            const closeBtn = document.querySelector(".closeModalPed");

            if (openBtn) {
                openBtn.addEventListener("click", () => {
                    modal.style.display = "flex";
                });
            }

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
        });
    </script>


    <!-- calculo total del modal -->
    <script>
    document.addEventListener("DOMContentLoaded", function(){

        const precioInput = document.getElementById("precio");
        const cantidadInput = document.getElementById("cantidad");
        const totalInput = document.getElementById("total");

        function calcularTotal() {
            const precio = parseFloat(precioInput.value) || 0;
            const cantidad = parseInt(cantidadInput.value) || 0;

            totalInput.value = (precio * cantidad).toFixed(2);
        }

        precioInput.addEventListener("input", calcularTotal);
        cantidadInput.addEventListener("input", calcularTotal);

        // Calcular al cargar
        calcularTotal();
    });
    </script>

    <!-- script para modal ver pedido -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            const modal = document.getElementById("idmodalViewPedidos");
            const closeBtn = document.querySelector(".closeModalViewPed");
            const botones = document.querySelectorAll(".openModalViewPedido");

            botones.forEach(btn => {
                btn.addEventListener("click", () => {

                    document.getElementById("viewCliente").textContent = btn.dataset.cliente;
                    document.getElementById("viewDescripcion").textContent = btn.dataset.descripcion;
                    document.getElementById("viewDireccion").textContent = btn.dataset.direccion;
                    document.getElementById("viewFecha").textContent = btn.dataset.fecha;
                    document.getElementById("viewPrecio").textContent = btn.dataset.precio;
                    document.getElementById("viewCantidad").textContent = btn.dataset.cantidad;
                    document.getElementById("viewTotal").textContent = btn.dataset.total;
                    document.getElementById("viewEstado").textContent = btn.dataset.estado;

                    modal.style.display = "flex";
                });
            });

            closeBtn.addEventListener("click", () => {
                modal.style.display = "none";
            });

            window.addEventListener("click", (e) => {
                if (e.target === modal) {
                    modal.style.display = "none";
                }
            });

        });
    </script>

    <!-- script para modal editar pedido -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            const modal = document.getElementById("idmodalEditPedidos");
            const closeBtn = document.querySelector(".closeModalEditPed");
            const botones = document.querySelectorAll(".openModalEditPedido");

            botones.forEach(btn => {

                btn.addEventListener("click", () => {

                    document.getElementById("editId").value = btn.dataset.id;
                    document.getElementById("editCliente").value = btn.dataset.cliente;
                    document.getElementById("editDescripcion").value = btn.dataset.descripcion;
                    document.getElementById("editDireccion").value = btn.dataset.direccion;
                    document.getElementById("editPrecio").value = btn.dataset.precio;
                    document.getElementById("editCantidad").value = btn.dataset.cantidad;

                    modal.style.display = "flex";
                });

            });

            closeBtn.addEventListener("click", () => {
                modal.style.display = "none";
            });

        });
    </script>

    <!-- script para modal eliminar pedido -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            const modal = document.getElementById("idmodalDeletePedidos");
            const botones = document.querySelectorAll(".openModalDeletePedido");
            const closeBtns = document.querySelectorAll(".closeModalDeletePed");

            botones.forEach(btn => {

                btn.addEventListener("click", () => {

                    document.getElementById("deleteId").value = btn.dataset.id;
                    document.getElementById("deleteCliente").textContent = btn.dataset.cliente;
                    document.getElementById("deleteDescripcion").textContent = btn.dataset.descripcion;
                    document.getElementById("deleteTotal").textContent = btn.dataset.total;

                    modal.style.display = "flex";
                });

            });

            closeBtns.forEach(btn => {
                btn.addEventListener("click", () => {
                    modal.style.display = "none";
                });
            });

            window.addEventListener("click", (e) => {
                if (e.target === modal) {
                    modal.style.display = "none";
                }
            });

        });
    </script>

    <!-- script para modal de link-->
    <script>
    document.addEventListener("DOMContentLoaded", () => {

        const modal = document.getElementById("modalLinkPedido");
        const botones = document.querySelectorAll(".openModalLinkPedido");
        const closeBtn = document.querySelector(".closeModalLinkPed");
        const inputLink = document.getElementById("inputLinkPedido");
        const btnCopiar = document.getElementById("copiarLinkPedido");

        botones.forEach(btn => {
            btn.addEventListener("click", () => {

                const token = btn.dataset.token;

                const urlBase = window.location.origin;
                const link = `${urlBase}/seguimiento.php?token=${token}`;

                inputLink.value = link;

                modal.style.display = "flex";
            });
        });

        closeBtn.addEventListener("click", () => {
            modal.style.display = "none";
        });

        window.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });

        btnCopiar.addEventListener("click", () => {
            inputLink.select();
            document.execCommand("copy");
            alert("Link copiado correctamente.");
        });

    });
    </script>

    
</body>
</html>
