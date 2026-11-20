<?php
require_once('../../helpers/dd.php');
require_once('../../controllers/functions.php');
require_once('../../src/partials/conexionBD.php');

controlAcceso($bd, ['cliente']);
$cliente_id = $_SESSION['cliente_id'];

// EXPORTAR CSV

// EXPORTAR CSV
if (isset($_GET['exportar']) && $_GET['exportar'] === 'csv') {

    // Traemos los pedidos del cliente
    $pedidosExport = listarPedidos($bd, $cliente_id);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=pedidos_' . date('Y-m-d') . '.csv');

    $output = fopen('php://output', 'w');

    // Encabezados del CSV
    fputcsv($output, [
        'ID Pedido',
        'Cliente',
        'Producto',
        'Precio unitario',
        'Cantidad',
        'Subtotal',
        'Direccion',
        'Observacion',
        'Fecha',
        'Total pedido',
        'Estado'
    ], ';');

    foreach ($pedidosExport as $ped) {

        // Recorrer los productos de cada pedido
        foreach ($ped['productos'] as $producto) {

            fputcsv($output, [
                'CDP-' . $ped['id'],
                $ped['publico_final'],
                $producto['descripcion'],
                $producto['precio'],
                $producto['cantidad'],
                $producto['subtotal'],
                $ped['direccion'],
                $ped['observacion'],
                $ped['fecha'],
                $ped['total'],
                $ped['estado_nombre']
            ], ';');
        }
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



// CREAR PEDIDO SIMPLE
if(isset($_POST['crear_pedido'])) {

    // Convertimos los datos del formulario simple
    // al formato que utiliza guardarPedido()
    $datosPedido = [
        'publico_final' => trim($_POST['publico_final'] ?? ''),
        'direccion' => trim($_POST['direccion'] ?? ''),
        'observacion' => trim($_POST['observacion'] ?? ''),
        'productos' => [
            [
                'descripcion' => $_POST['descripcion'] ?? '',
                'precio' => $_POST['precio'] ?? '',
                'cantidad' => $_POST['cantidad'] ?? ''
            ]
        ]
    ];

    $errores = validarPedido($datosPedido);

    if(empty($errores)) {

        $guardado = guardarPedido($bd, $datosPedido);

        if($guardado) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $errores[] = "Error al guardar el pedido.";
        }
    }
}


// CREAR PEDIDO MÚLTIPLE
if(isset($_POST['crear_pedido_multiple'])) {

    $errores = validarPedido($_POST);

    if(empty($errores)) {

        $guardado = guardarPedido($bd, $_POST);

        if($guardado) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $errores[] = "Error al guardar el pedido múltiple.";
        }
    }
}


// CREAR PEDIDO RÁPIDO
if(isset($_POST['crear_pedido_rapido'])) {

    $errores = validarPedidoRapido($_POST);

    if (empty($errores)) {

        $datosPedido = [
            'publico_final' => trim($_POST['publico_final']),
            'direccion' => '',
            'observacion' => '',
            'productos' => [
                [
                    'descripcion' => trim($_POST['descripcion']),
                    'precio' => $_POST['precio'],
                    'cantidad' => 1
                ]
            ]
        ];

        $guardado = guardarPedido($bd, $datosPedido);

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
                        <div class="rspCardPedido">
                            <span class="iconify-inline" data-icon="lsicon:order-edit-outline">
                        </div>
                    </button>
                    <button class="cardsPedidos openModalPedidoRapido addPedidoBtn">
                        <div class="titleCardPedidos">Registrar pedido rápido:</div>
                        <div class="rspCardPedido">
                            <span class="iconify-inline" data-icon="solar:sort-by-time-linear"></span>
                        </div>
                    </button>
                </div>

                <section class="containerTablePedidos">
                    <section class="containerFormSearPed">
                        <form class="pedidosSearchForm" role="search" action="#" method="GET">
                            <input class="form-control" type="search" placeholder="Buscador..." aria-label="Search" name="busquedaPedidos">
                            <select name="tipoBusquedaPedidos" id="tipoBusquedaPedidos">
                                <option value="publico_final">Por cliente</option>
                                <option value="id">Por ID</option>
                                <option value="descripcion">Por producto</option>
                                <option value="fecha">Por fecha</option>
                                <option value="estado">Por estado</option>
                                <option value="total">Por total del pedido</option>
                            </select>
                            <button class="btn btnSearchFormPed" data-bs-toggle="collapse" data-bs-target="#verUsuarios" aria-expanded="<?= $busquedaActivaClientes? 'true' : 'false' ?>" aria-controls="verClientes">Buscar</button>
                        </form>
                        <div class="contDocsPed">
                            <a href="?exportar=csv" class="ExpCsvPed">Exportar CSV</a>
                        </div>
                    </section>

                    <section class="contaTablePed">
                        <table class="table table-hover tablaPedidos" id="tablaPedidos">
                            <thead>
                                <tr class="table-primary">
                                    <th>Id</th> <!-- 1 -->
                                    <th>Cliente</th> <!-- 2 -->
                                    <th>Productos</th> <!-- 3 -->
                                    <th>Dirección de envío</th> <!-- 4 -->
                                    <th>Fecha</th> <!-- 5 -->
                                    <th>Total</th> <!--6 -->
                                    <th>Estado</th> <!--7 -->
                                    <th>Acción</th> <!--8 -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($pedido as $id =>$pedidos) :?>
                                <tr>
                                    <td>CDP-<?=  $pedidos['id'] ?></td>
                                    <td><?=  $pedidos['publico_final'] ?></td>
                                    <td>
                                        <?php
                                            $productos = $pedidos['productos'] ?? [];
                                            $totalProductos = count($productos);
                                        ?>

                                        <?php if($totalProductos > 0): ?>

                                            <div class="listaProductosTabla">

                                                <!-- Mostrar el primer producto -->
                                                <div class="productoPrincipal">
                                                    <?= htmlspecialchars($productos[0]['descripcion']) ?>
                                                    <span>
                                                        × <?= $productos[0]['cantidad'] ?>
                                                    </span>
                                                </div>

                                                <!-- Indicar cuántos productos adicionales existen -->
                                                <?php if($totalProductos > 1): ?>
                                                    <small class="productosAdicionales">
                                                        + <?= $totalProductos - 1 ?> productos más
                                                    </small>
                                                <?php endif; ?>

                                            </div>

                                        <?php else: ?>
                                            <span class="text-muted">Sin productos</span>
                                        <?php endif; ?>
                                    </td>
                                    <td title="<?= htmlspecialchars($pedidos['direccion']) ?>" data-bs-toggle="tooltip"><?=  $pedidos['direccion'] ?></td>
                                    <td><?=  $pedidos['fecha'] ?></td>
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
                                                <div class="selectorEstado">
                                                    <span class="estado estado-<?= strtolower(trim($pedidos['estado_nombre'])) ?>">
                                                        <span class="indicadorEstado"></span>
                                                        <?= ucwords(str_replace('_', ' ', $pedidos['estado_nombre'])) ?>
                                                    </span>
                                                </div>
                                                
                                            <?php else: ?>
                                                <div class="selectorEstado estado-<?= strtolower(trim($pedidos['estado_nombre'])) ?>">
                                                    <span class="indicadorEstado"></span>
                                                    <select name="nuevo_estado" class="estado">
                                                        <?php foreach($estadosDisponibles as $estado): ?>
                                                            <option value="<?= $estado['id'] ?>" <?= ($estado['id'] == $estadoActual) ? 'selected' : '' ?> >
                                                                <?= ucfirst($estado['nombre']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>    
                                                <button type="submit" name="cambiar_estado" class="checkEstado">
                                                    <span class="iconify-inline" data-icon="el:ok-sign"></span>
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    </td>

                                    <td class="contAccionNegocios">
                                        <button class="openModalViewPedido"
                                            data-cliente="<?= htmlspecialchars($pedidos['publico_final']) ?>"
                                            data-descripcion="<?= htmlspecialchars($pedidos['descripcion']) ?>"
                                            data-observacion="<?= htmlspecialchars($pedidos['observacion']) ?>"
                                            data-direccion="<?= htmlspecialchars($pedidos['direccion']) ?>"
                                            data-fecha="<?= $pedidos['fecha'] ?>"
                                            data-precio="<?= $pedidos['precio'] ?>"
                                            data-cantidad="<?= $pedidos['cantidad'] ?>"
                                            data-total="<?= $pedidos['total'] ?>"
                                            data-estado="<?= htmlspecialchars($pedidos['estado_nombre']) ?>"
                                            data-productos="<?= htmlspecialchars(
                                                json_encode(
                                                    $pedidos['productos'] ?? [],
                                                    JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP
                                                ),
                                                ENT_QUOTES, 'UTF-8' ) ?>" >
                                            <span class="iconify-inline iconTabPed" data-icon="carbon:view"></span>
                                        </button>
                                        
                                        <?php if($pedidos['estado_id'] != 5 && $pedidos['estado_id'] != 6): ?>
                                        <button class="openModalEditPedido"
                                            data-id="<?= $pedidos['id'] ?>"
                                            data-cliente="<?= htmlspecialchars($pedidos['publico_final'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-observacion="<?= htmlspecialchars($pedidos['observacion'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                            data-direccion="<?= htmlspecialchars($pedidos['direccion'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                            data-productos="<?= htmlspecialchars(
                                                json_encode(
                                                    $pedidos['productos'] ?? [],
                                                    JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>"
                                        >
                                            <span class="iconify-inline iconTabPed" data-icon="cil:pen"></span>
                                        </button>
                                        <?php endif; ?>

                                        <button class="openModalDeletePedido"
                                            data-id="<?= $pedidos['id'] ?>"
                                            data-cliente="<?= htmlspecialchars($pedidos['publico_final']) ?>"
                                            data-descripcion="<?= htmlspecialchars($pedidos['descripcion']) ?>"
                                            data-total="<?= $pedidos['total'] ?>"
                                        >
                                            <span class="iconify-inline iconTabPed" data-icon="solar:trash-bin-2-linear"></span>
                                        </button>

                                        <button class="openModalLinkPedido"
                                            data-token="<?= htmlspecialchars($pedidos['token_publico']) ?>">
                                            <span class="iconify-inline iconTabPed" data-icon="line-md:link"></span>
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

                <!-- Pestañas -->
                <div class="d-flex gap-2 mb-3">
                    <button type="button" class="btn btn-primary flex-fill tipoPedidoBtn active btnPestañaAdd" data-tipo="simple">
                        Pedido simple
                    </button>

                    <button type="button" class="btn btn-outline-primary flex-fill tipoPedidoBtn btnPestañaAdd" data-tipo="multiple">
                        Pedido múltiple
                    </button>
                </div>

                <!-- FORMULARIO PEDIDO SIMPLE -->
                <form method="POST" id="formPedidoSimple" class="formPedidoSimple">

                    <input type="hidden" name="crear_pedido" value="1">

                    <div class="mb-2">
                        <label class="form-label m-0">Nombre del cliente</label>
                        <input type="text" name="publico_final" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label m-0">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="4"
                            placeholder="Escribe una descripción por línea..." required></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="form-label m-0">Precio</label>
                        <input type="number" step="0.01" min="0.01"
                            name="precio" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label m-0">Cantidad</label>
                        <input type="number" min="1" step="1" value="1"
                            name="cantidad" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label m-0">Dirección</label>
                        <input type="text" name="direccion" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label class="form-label m-0">Observación</label>
                        <textarea name="observacion" class="form-control" rows="3"
                            placeholder="Información adicional (opcional)..."></textarea>
                    </div>

                    <div class="mt-4 d-grid">
                        <button type="submit" class="btnAddPedido">
                            Registrar pedido
                        </button>
                    </div>

                </form>


                <!-- FORMULARIO PEDIDO MÚLTIPLE -->
                <form method="POST" id="formPedidoMultiple" style="display:none;" class="formPedidoMultiple">

                    <input type="hidden" name="crear_pedido_multiple" value="1">

                    <div class="mb-2">
                        <label class="form-label m-0">Nombre del cliente</label>
                        <input type="text" name="publico_final" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label m-0">Dirección</label>
                        <input type="text" name="direccion" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label class="form-label m-0">Observación</label>
                        <textarea name="observacion" class="form-control" rows="3"
                            placeholder="Información adicional (opcional)..."></textarea>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Productos del pedido</strong>

                        <button type="button" id="btnAgregarProductoAdd"
                            class="btn btnAddProductoMultiple">
                            +<span>Agregar producto</span>
                        </button>
                    </div>

                    <div id="contenedorProductosAdd"></div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <strong>Total del pedido:</strong>
                        <strong id="totalPedidoAdd">S/ 0.00</strong>
                    </div>

                    <div class="mt-4 d-grid">
                        <button type="submit" class="btnAddPedido">
                            Registrar pedido
                        </button>
                    </div>

                </form>

            </div>
        </div>

        <!-- Modal registrar pedido rápido -->
        <div class="modalOverlayPedRap" id="modalAddPedidoRapido">
            <div class="modalContentPed">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 titleModal">REGISTRO RÁPIDO</h5>

                    <button type="button" class="btn-close closeModalPedRapido"></button>
                </div>

                <div class="alert alert-info small">
                    <strong>Registro rápido:</strong>
                    utiliza esta opción cuando necesites registrar varios pedidos rápidamente.
                    Luego puedes editar cada pedido para completar información como la dirección
                    y mejorar su seguimiento.
                </div>

                <?php if (isset($_POST['crear_pedido_rapido']) && count($errores) > 0): ?>

                    <ul class="alert alert-danger">
                        <?php foreach ($errores as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>

                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="crear_pedido_rapido" value="1">
                    <div class="mb-2">
                        <label class="form-label m-0">
                            Nombre del cliente
                        </label>
                        <input
                            type="text"
                            name="publico_final"
                            class="form-control m-0"
                            required
                        >
                    </div>
                    <div class="mb-2">
                        <label class="form-label m-0">
                            Descripción
                        </label>

                        <input
                            type="text"
                            name="descripcion"
                            class="form-control m-0"
                            required
                        >
                    </div>

                    <div class="mb-2">
                        <label class="form-label m-0">
                            Precio
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="precio"
                            class="form-control m-0"
                            required
                        >
                    </div>

                    <div class="mt-4 d-grid">
                        <button type="submit" class="btnAddPedido">
                            Registrar rápidamente
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

                <div class="mb-2">
                    <h6>Observación</h6>
                    <p id="viewObservacion"></p>
                </div>

                <div class="mb-2">
                    <h6>Dirección</h6>
                    <p id="viewDireccion"></p>
                </div>

                <div class="mb-2">
                    <h6>Fecha</h6>
                    <p id="viewFecha"></p>
                </div>
                <hr>

                <div class="mb-2">
                    <h6>Productos del pedido</h6>

                    <div id="viewProductos" class="listaProductosView">
                        <!-- Los productos se cargarán dinámicamente -->
                    </div>
                </div>

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

                <form action="" method="POST" id="formEditarPedido">

                    <input type="hidden" name="editar_pedido" value="1">
                    <input type="hidden" name="id" id="editId">

                    <!-- Datos generales -->
                    <label>Cliente</label>
                    <input type="text" class="form-control mb-3" name="publico_final" id="editCliente" required >

                    <label>Dirección</label>
                    <input type="text" class="form-control mb-3" name="direccion" id="editDireccion" >

                    <label class="form-label m-0">Observación</label>
                    <textarea name="observacion" id="editObservacion" class="form-control mb-3" rows="3" placeholder="Agrega alguna información adicional (opcional)..." ></textarea>

                    <hr>

                    <!-- Productos -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0" style="font-weight: bold;">PRODUCTOS DEL PEDIDO</h6>

                        <button type="button" id="btnAgregarProductoEdit" class="btn  btnaddPedidoEdit">
                            + Agregar producto
                        </button>
                    </div>

                    <div id="contenedorProductosEdit"></div>

                    <hr>

                    <!-- Total general -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <strong>Total del pedido:</strong>
                        <strong id="totalPedidoEdit">S/ 0.00</strong>
                    </div>

                    <button type="submit" class="btnUpdPedido">
                        Guardar Cambios
                    </button>

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
                    <span class="iconify-inline iconTabPed iconAlert" data-icon="mingcute:alert-fill"></span>
                    ¿Estás seguro que quieres eliminar el pedido del cliente 
                    <strong id="deleteCliente"></strong>?
                    <span class="iconify-inline iconTabPed iconAlert" data-icon="mingcute:alert-fill"></span>
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
            <?php include_once('../../src/partials/footer.php')?>
        </footer>
        
        
    </section>
    



    <!-- Script: Apertura, cierre y selección del tipo de pedido -->
    <script>        
        document.addEventListener("DOMContentLoaded", () => {

            const modal = document.getElementById("modalAddPedidos");
            const openBtn = document.querySelector(".openModalPedido");
            const closeBtn = document.querySelector(".closeModalPed");

            const botonesTipo = document.querySelectorAll(".tipoPedidoBtn");

            const formSimple = document.getElementById("formPedidoSimple");
            const formMultiple = document.getElementById("formPedidoMultiple");

            function cambiarTipoPedido(tipo) {

                const esSimple = tipo === "simple";

                formSimple.style.display = esSimple ? "block" : "none";
                formMultiple.style.display = esSimple ? "none" : "block";

                botonesTipo.forEach(btn => {

                    const seleccionado = btn.dataset.tipo === tipo;

                    btn.classList.toggle("btn-primary", seleccionado);
                    btn.classList.toggle("btn-outline-primary", !seleccionado);
                    btn.classList.toggle("active", seleccionado);

                });
            }

            botonesTipo.forEach(btn => {
                btn.addEventListener("click", () => {
                    cambiarTipoPedido(btn.dataset.tipo);
                });
            });

            if (openBtn) {
                openBtn.addEventListener("click", () => {
                    cambiarTipoPedido("simple");
                    modal.style.display = "flex";
                });
            }

            if (closeBtn) {
                closeBtn.addEventListener("click", () => {
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

    <!-- Script: Agregar, eliminar y calcular productos del pedido múltiple -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            const contenedor = document.getElementById("contenedorProductosAdd");
            const btnAgregar = document.getElementById("btnAgregarProductoAdd");
            const totalGeneral = document.getElementById("totalPedidoAdd");

            if (!contenedor || !btnAgregar || !totalGeneral) return;

            let contadorProductos = 0;

            function calcularTotal() {

                let total = 0;

                const productos = contenedor.querySelectorAll(".productoAdd");

                productos.forEach(producto => {

                    const precio = parseFloat(
                        producto.querySelector(".precioProductoAdd").value
                    ) || 0;

                    const cantidad = parseInt(
                        producto.querySelector(".cantidadProductoAdd").value
                    ) || 0;

                    const subtotal = precio * cantidad;

                    producto.querySelector(".subtotalProductoAdd").textContent =
                        `S/ ${subtotal.toFixed(2)}`;

                    total += subtotal;
                });

                totalGeneral.textContent = `S/ ${total.toFixed(2)}`;
            }

            function agregarProducto() {

                const indice = contadorProductos++;

                const fila = document.createElement("div");

                fila.className = "productoAdd border rounded p-3 mb-3";

                fila.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Producto ${indice + 1}</strong>

                        <button type="button"
                            class="btn btn-sm btn-outline-danger btnEliminarProductoAdd">
                            Eliminar
                        </button>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Descripción</label>

                        <textarea
                            name="productos[${indice}][descripcion]"
                            class="form-control descripcionProductoAdd"
                            rows="3"
                            required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">Precio</label>

                            <input type="number"
                                name="productos[${indice}][precio]"
                                class="form-control precioProductoAdd"
                                min="0.01"
                                step="0.01"
                                required>
                        </div>

                        <div class="col-6">
                            <label class="form-label">Cantidad</label>

                            <input type="number"
                                name="productos[${indice}][cantidad]"
                                class="form-control cantidadProductoAdd"
                                min="1"
                                step="1"
                                value="1"
                                required>
                        </div>
                    </div>

                    <div class="text-end mt-2">
                        Subtotal:
                        <strong class="subtotalProductoAdd">S/ 0.00</strong>
                    </div>
                `;

                fila.querySelector(".precioProductoAdd")
                    .addEventListener("input", calcularTotal);

                fila.querySelector(".cantidadProductoAdd")
                    .addEventListener("input", calcularTotal);

                fila.querySelector(".btnEliminarProductoAdd")
                    .addEventListener("click", () => {

                        const cantidadProductos =
                            contenedor.querySelectorAll(".productoAdd").length;

                        if (cantidadProductos <= 1) {
                            alert("El pedido debe tener al menos un producto.");
                            return;
                        }

                        fila.remove();
                        calcularTotal();
                    });

                contenedor.appendChild(fila);

                calcularTotal();
            }

            btnAgregar.addEventListener("click", agregarProducto);

            // Inicializar el primer producto al seleccionar pedido múltiple
            const botonesTipo = document.querySelectorAll(".tipoPedidoBtn");

            botonesTipo.forEach(btn => {
                btn.addEventListener("click", () => {
                    if (btn.dataset.tipo === "multiple") {
                        const cantidadProductos =
                            contenedor.querySelectorAll(".productoAdd").length;
                        if (cantidadProductos === 0) {
                            agregarProducto();
                        }
                    }
                });
            });

        });

    </script>

    <!-- script para abrir/cerrar el modal pedido rapido-->    
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const modalPedidoRapido = document.getElementById("modalAddPedidoRapido");
        const btnPedidoRapido = document.querySelector(".openModalPedidoRapido");
        const btnCerrarPedidoRapido = document.querySelector(".closeModalPedRapido");

        if (btnPedidoRapido) {
            btnPedidoRapido.addEventListener("click", () => {
                modalPedidoRapido.style.display = "flex";
            });
        }

        if (btnCerrarPedidoRapido) {
            btnCerrarPedidoRapido.addEventListener("click", () => {
                modalPedidoRapido.style.display  = "none";
            });
        }

        window.addEventListener("click", (e) => {
            if (e.target === modalPedidoRapido) {
                modalPedidoRapido.style.display = "none";
            }
        });

    });
    </script> 

    <!-- script calculo total del modal -->
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

                    // Datos generales
                    document.getElementById("viewCliente").textContent = btn.dataset.cliente;
                    document.getElementById("viewObservacion").textContent = btn.dataset.observacion;
                    document.getElementById("viewDireccion").textContent = btn.dataset.direccion;
                    document.getElementById("viewFecha").textContent = btn.dataset.fecha;
                    document.getElementById("viewTotal").textContent = "S/ " + Number(btn.dataset.total).toFixed(2);
                    document.getElementById("viewEstado").textContent = btn.dataset.estado;

                    // Obtener productos
                    const productos = JSON.parse(btn.dataset.productos || "[]");
                    const contenedor = document.getElementById("viewProductos");

                    // Limpiar productos anteriores
                    contenedor.innerHTML = "";

                    // Recorrer productos
                    productos.forEach(producto => {

                        const card = document.createElement("div");
                        card.className = "productoViewCard";

                        const informacion = document.createElement("div");
                        informacion.className = "productoViewInfo";

                        const descripcion = document.createElement("strong");
                        descripcion.textContent = producto.descripcion;

                        const detalle = document.createElement("small");
                        detalle.textContent = `S/ ${Number(producto.precio).toFixed(2)} × ${producto.cantidad}`;

                        const subtotal = document.createElement("strong");
                        subtotal.className = "productoViewSubtotal";
                        subtotal.textContent = "S/ " + Number(producto.subtotal).toFixed(2);

                        informacion.appendChild(descripcion);
                        informacion.appendChild(detalle);

                        card.appendChild(informacion);
                        card.appendChild(subtotal);

                        contenedor.appendChild(card);
                    });

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

            const contenedor = document.getElementById("contenedorProductosEdit");
            const btnAgregar = document.getElementById("btnAgregarProductoEdit");
            const totalGeneral = document.getElementById("totalPedidoEdit");

            let contadorProductos = 0;

            // Formatear moneda
            function formatoMoneda(valor){
                return Number(valor).toFixed(2);
            }

            // Calcular subtotal y total general
            function calcularTotal(){

                let total = 0;

                const productos = contenedor.querySelectorAll(".productoEdit");

                productos.forEach(producto => {

                    const precio = parseFloat(producto.querySelector(".precioProductoEdit").value) || 0;
                    const cantidad = parseInt(producto.querySelector(".cantidadProductoEdit").value) || 0;

                    const subtotal = precio * cantidad;

                    producto.querySelector(".subtotalProductoEdit").textContent =
                        `S/ ${formatoMoneda(subtotal)}`;

                    total += subtotal;
                });

                totalGeneral.textContent = `S/ ${formatoMoneda(total)}`;
            }

            // Agregar producto al modal
            function agregarProducto(producto = {}){

                const indice = contadorProductos++;

                const descripcion = producto.descripcion ?? "";
                const precio = producto.precio ?? "";
                const cantidad = producto.cantidad ?? 1;

                const fila = document.createElement("div");

                fila.className = "productoEdit border rounded p-3 mb-3";

                fila.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>Producto</div>

                        <button type="button" class="btn btn-sm btn-outline-danger btnEliminarProductoEdit">
                            <span class="iconify-inline" data-icon="solar:trash-bin-2-linear"></span>
                            Quitar
                        </button>
                    </div>

                    <label>Descripción</label>
                    <textarea name="productos[${indice}][descripcion]" class="form-control mb-2 descripcionProductoEdit" rows="2" required ></textarea>

                    <div class="row">
                        <div class="col-6">
                            <label>Precio</label>
                            <input type="number" name="productos[${indice}][precio]" class="form-control precioProductoEdit" min="0.01" step="0.01" required >
                        </div>

                        <div class="col-6">
                            <label>Cantidad</label>
                            <input type="number" name="productos[${indice}][cantidad]" class="form-control cantidadProductoEdit" min="1" step="1" required >
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <strong class="subtotalProductoEdit">S/ 0.00</strong>
                    </div>
                `;

                // Asignar valores
                fila.querySelector(".descripcionProductoEdit").value = descripcion;
                fila.querySelector(".precioProductoEdit").value = precio;
                fila.querySelector(".cantidadProductoEdit").value = cantidad;

                // Calcular al modificar precio o cantidad
                fila.querySelector(".precioProductoEdit").addEventListener("input", calcularTotal);
                fila.querySelector(".cantidadProductoEdit").addEventListener("input", calcularTotal);

                // Eliminar producto
                fila.querySelector(".btnEliminarProductoEdit").addEventListener("click", () => {

                    const cantidadProductos = contenedor.querySelectorAll(".productoEdit").length;

                    if(cantidadProductos <= 1){
                        alert("El pedido debe tener al menos un producto.");
                        return;
                    }

                    fila.remove();
                    calcularTotal();
                });

                contenedor.appendChild(fila);

                calcularTotal();
            }

            // Abrir modal y cargar pedido
            botones.forEach(btn => {

                btn.addEventListener("click", () => {

                    document.getElementById("editId").value = btn.dataset.id;
                    document.getElementById("editCliente").value = btn.dataset.cliente;
                    document.getElementById("editDireccion").value = btn.dataset.direccion;
                    document.getElementById("editObservacion").value = btn.dataset.observacion ?? "";

                    // Limpiar productos anteriores
                    contenedor.innerHTML = "";
                    contadorProductos = 0;

                    // Recuperar productos desde JSON
                    let productos = [];

                    try {
                        productos = JSON.parse(btn.dataset.productos);
                    } catch(error) {
                        console.error("Error al cargar los productos:", error);
                    }

                    // Cargar productos existentes
                    if(Array.isArray(productos) && productos.length > 0){

                        productos.forEach(producto => {
                            agregarProducto(producto);
                        });

                    } else {

                        // Compatibilidad por si un pedido todavía no tiene detalles
                        agregarProducto();
                    }

                    modal.style.display = "flex";
                });

            });

            // Agregar nuevo producto
            btnAgregar.addEventListener("click", () => {
                agregarProducto();
            });

            // Cerrar modal
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
                
                // Ruta base de tu proyecto en LOCAL
                const basePath = "/Paginas_web/SUFI/gestor_v2";
                // Si tu web está en dominio raíz:
                //const basePath = "";

                const urlBase = window.location.origin;
                const link = `${urlBase}${basePath}/seguimiento.php?token=${token}`;

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
