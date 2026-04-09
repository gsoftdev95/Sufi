<?php
require_once('../../helpers/dd.php');
require_once('../../controllers/functions.php');
require_once('../../src/partials/conexionBD.php');

controlAcceso($bd, ['admin']);
$errores = [];
$exito = false;

$clientes = obtenerClientes($bd);
$notificaciones = obtenerNotificacionesAdmin($bd);


if(isset($_POST['enviar'])){

    $titulo = trim($_POST['titulo']);
    $mensaje = trim($_POST['mensaje']);
    $tipo_envio = $_POST['tipo_envio'];
    $cliente_id = $_POST['cliente_id'] ?? null;
    $admin_id = $_SESSION['id'];

    if(empty($titulo) || empty($mensaje)){
        $errores[] = "Todos los campos son obligatorios";
    }

    if($tipo_envio === 'uno' && empty($cliente_id)){
        $errores[] = "Debe seleccionar un cliente";
    }

    if(empty($errores)){
        if(enviarNotificacion($bd, $admin_id, $titulo, $mensaje, $tipo_envio, $cliente_id)){
            header("Location: notificaciones.php?enviado=1");
            exit;
        }
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

                <h3 class="mt-3">Notificaciones</h3>

                <div class="containerCardsNotificaciones">
                    <div class="cardNoti crearNotiBtn">
                        <div class="crearNot">Enviar mensaje</div>
                        <div class="rspCard"><span class="iconify-inline iconCardNegocio" data-icon="material-symbols:notification-add"></span></div>
                    </div>                    
                </div>
                
                <section class="containerTableNotificaciones">
                    <section class="containerFormSearNot">
                    </section>

                    <div class="contTableInner contTableInnerNot">
                        <table class="table table-hover">
                            <thead>
                                <tr class="table-primary">
                                    <th>Id</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Título</th>
                                    <th>Mensaje</th>
                                    <th>Leido</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($notificaciones)): ?>
                                    <?php foreach($notificaciones as $n): ?>
                                        <tr>
                                            <td><?= $n['id'] ?></td>
                                            <td><?= date('d/m/Y', strtotime($n['creado_en'])) ?></td>
                                            <td><?= htmlspecialchars($n['nombre_negocio']) ?></td>
                                            <td><?= htmlspecialchars($n['titulo']) ?></td>
                                            <td><?= htmlspecialchars($n['mensaje']) ?></td>
                                            <td>
                                                <?php if($n['leido']): ?>
                                                    <span class="text-success">Sí</span>
                                                <?php else: ?>
                                                    <span class="text-danger">No</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-secondary">
                                                    Reenviar
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            No hay notificaciones enviadas.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                        </table>
                    </div>
                </section>
                

            </div>
        </section>


        <!--Modal para enviar notificación-->
        <div class="modalOverlayCrearNoti" id="modalCrearNoti">
            <div class="modalContentCrearNoti">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Enviar notificación</h5>
                    <button type="button" class="btn-close closeModalCrearNoti"></button>
                </div>

                <?php if (count($errores) > 0) : ?>
                    <ul class="alert alert-danger">
                        <?php foreach ($errores as $error) : ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <?php if(isset($_GET['enviado'])): ?>
                    <div class="alert alert-success text-center">
                        Notificación enviada correctamente.
                    </div>
                <?php endif; ?>


                <form method="POST" class="formCrearNoti">

                    <input type="text" name="titulo" placeholder="Título" class="form-control my-2" required>

                    <textarea name="mensaje" placeholder="Mensaje" class="form-control my-2" required></textarea>

                    <select name="tipo_envio" id="tipoEnvio" class="form-select my-2">
                        <option value="uno">Un cliente</option>
                        <option value="todos">Todos los clientes</option>
                    </select>

                    <select name="cliente_id" id="clienteSelect" class="form-select my-2">
                        <option value="">Seleccione cliente</option>
                        <?php foreach($clientes as $c): ?>
                            <option value="<?= $c['id'] ?>">
                                <?= htmlspecialchars($c['nombre_negocio']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" name="enviar" class="EnviarNoti my-2">
                        Enviar
                    </button>

                </form>
            </div>
        </div>

        <footer>
            footer
        </footer>
        
    </section>

    <!--script para crear notificacion-->
    <script>
        const modal = document.getElementById("modalCrearNoti");
        const openBtn = document.querySelector(".crearNotiBtn");
        const closeBtn = document.querySelector(".closeModalCrearNoti");

        openBtn.addEventListener("click", ()=>{
            modal.style.display = "flex";
        })

        closeBtn.addEventListener("click", () => {
            modal.style.display = "none";
        });

        window.addEventListener("click", (e) => {
            if(e.target === modal){
                modal.style.display = "none";
            }
        });

    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {

            const tipoEnvio = document.getElementById("tipoEnvio");
            const clienteSelect = document.getElementById("clienteSelect");

            tipoEnvio.addEventListener("change", () => {
                clienteSelect.style.display =
                    tipoEnvio.value === "todos" ? "none" : "block";
            });

        });
    </script>

    
</body>
</html>
