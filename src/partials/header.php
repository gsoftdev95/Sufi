<?php
function obtenerUsername($bd, $cliente_id){
    $sql = "SELECT username FROM usuarios WHERE cliente_id = :cliente_id ORDER BY id ASC LIMIT 1";
    $stmt = $bd->prepare($sql);
    $stmt->execute([
        ':cliente_id' => $cliente_id
    ]);
    return $stmt->fetchColumn();
}
function obtenerFechaFinSuscripcion($bd, $cliente_id){
    $sql = "SELECT fecha_fin FROM clientes WHERE id = :cliente_id";
    $stmt = $bd->prepare($sql);
    $stmt->execute([
        ':cliente_id' => $cliente_id
    ]);
    return $stmt->fetchColumn();
}
function obtenerFechaInicioSuscripcion($bd, $cliente_id){
    $sql = "SELECT creado_en FROM usuarios WHERE cliente_id = :cliente_id ORDER BY id ASC LIMIT 1";
    $stmt = $bd->prepare($sql);
    $stmt->execute([
        ':cliente_id' => $cliente_id
    ]);
    return $stmt->fetchColumn();
}

$notificacionesCliente = [];
$noLeidas = 0;

if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'cliente'){
    $fecha_fin = obtenerFechaFinSuscripcion($bd, $_SESSION['cliente_id']);

    // username
    $username = obtenerUsername($bd, $_SESSION['cliente_id']);

    // Fecha de inicio
    $fecha_inicio = obtenerFechaInicioSuscripcion($bd, $_SESSION['cliente_id']);

    /*notificaciones*/
    $notificacionesCliente = obtenerNotificacionesCliente($bd, $_SESSION['cliente_id']);
    $noLeidas = contarNotificacionesNoLeidas($bd, $_SESSION['cliente_id']);
}




?>

<div class="bannerPlataformaInner">
    <div class="logoPrincipal">
        <img src="../../src/img/logo_admin/pequeña-sf.png" alt="foto de proveedor">
    </div>
    <div class="iconAccesos">
        
        <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'cliente'): ?>
            <?php
                $diasRestantes = null;

                if (!empty($fecha_fin)) {
                    $hoy = new DateTime('today');
                    $vencimiento = new DateTime($fecha_fin);
                    $diferencia = $hoy->diff($vencimiento);
                    $diasRestantes = $vencimiento < $hoy ? 0 : $diferencia->days;
                }
            ?>

            <div class="estadoSuscripcion conteinerIconoHeader">
                <span class="iconUsers noti" id="iconEstado">
                    <i class="bi bi-check-circle-fill"></i>
                </span>

                <div class="tooltipSuscripcion" id="tooltipSuscripcion">
                    <div class="suscripcionUsuario">
                        <i class="bi bi-person-circle"></i>
                        <div>
                            <strong>@<?= htmlspecialchars($username  ?? 'usuario ') ?></strong>
                            <span>Cuenta de usuario</span>
                        </div>
                    </div>

                    <div class="suscripcionHeader">
                        <div class="suscripcionIcon">
                            <i class="bi bi-shield-check"></i>
                        </div>

                        <div>
                            <strong>Mi suscripción</strong>
                            <span class="suscripcionEstado">
                                <i class="bi bi-circle-fill"></i> Activa
                            </span>
                        </div>
                    </div>

                    <div class="suscripcionBody">
                        <div class="suscripcionItem">
                            <span><i class="bi bi-box-seam"></i> Plan contratado</span>
                            <strong>SUFI mensual</strong>
                        </div>
                        <div class="suscripcionItem">
                            <span><i class="bi bi-calendar-check"></i> Fecha de inicio</span>
                            <strong><?= !empty($fecha_inicio) ? date('d/m/Y', strtotime($fecha_inicio)) : '---' ?></strong>
                        </div>
                        <div class="suscripcionItem">
                            <span><i class="bi bi-calendar-event"></i> Fecha de vencimiento</span>
                            <strong><?= !empty($fecha_fin) ? date('d/m/Y', strtotime($fecha_fin)) : '---' ?></strong>
                        </div>
                    </div>

                    <div class="suscripcionFooter">
                        <i class="bi bi-clock-history"></i>

                        <?php if ($diasRestantes !== null): ?>
                            <?php if ($diasRestantes > 0): ?>
                                Te quedan <strong><?= $diasRestantes ?> días</strong> de servicio.
                            <?php else: ?>
                                <strong>Tu periodo de servicio ha finalizado.</strong>
                            <?php endif; ?>
                        <?php else: ?>
                            Fecha de vencimiento no disponible.
                        <?php endif; ?>
                    </div>

                </div>
            </div>

        <?php endif; ?>

        <div class="conteinerIconoHeader">
            <span class="iconUsers msg" id="iconNotificaciones">
                <i class="bi bi-chat-dots-fill"></i>
                <?php if($noLeidas > 0): ?>
                    <span class="numberNotif"><?= $noLeidas ?></span>
                <?php endif; ?>
            </span>                    
        </div>
        
        <div class="conteinerIconoHeader">
            <a href="../../logout.php">
                <span class="iconUsers logout">
                    <i class="bi bi-x-circle-fill"></i>
                </span>
            </a>
        </div>
        
    </div>
</div>


<!--modal notificaciones para clientes-->
<div class="modalNotificaciones" id="modalNotificaciones">
    <div class="modalContenidoNotificaciones">

        <div class="d-flex justify-content-between mb-3">
            <div>
                <h6>Notificaciones</h6>
                <p class="notaNoti">presione el mensaje para marcar como leído</p>
            </div>
            <button type="button" class="btn-close cerrarModalNoti" id="cerrarModalNoti"></button>
        </div>

        <?php if(!empty($notificacionesCliente)): ?>
            <?php foreach($notificacionesCliente as $n): ?>
                <div class="cardNotificacion <?= $n['leido'] ? 'leida' : 'noLeida' ?>" data-id="<?= $n['id'] ?>">
                    <div class="tituloNoti"><?= htmlspecialchars($n['titulo']) ?></div>
                    <div class="mensajeNoti"><?= htmlspecialchars($n['mensaje']) ?></div>
                    <div class="fechaNoti">
                        <?= date('d/m/Y H:i', strtotime($n['creado_en'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No tienes notificaciones.</p>
        <?php endif; ?>

    </div>
</div>



<!--script modal notificaiones-->
<script>
    document.addEventListener("DOMContentLoaded", ()=>{

        const iconNotificaciones = document.getElementById("iconNotificaciones");
        const modalNotificaciones = document.getElementById("modalNotificaciones");
        const cerrarModalNoti = document.getElementById("cerrarModalNoti");

        if(iconNotificaciones){
            iconNotificaciones.addEventListener("click", ()=>{
                modalNotificaciones.style.display = "flex";
            });
        }

        if(cerrarModalNoti){
            cerrarModalNoti.addEventListener("click", ()=>{
                modalNotificaciones.style.display = "none";
            });
        }

        window.addEventListener("click", (e)=>{
            if(e.target === modalNotificaciones){
                modalNotificaciones.style.display = "none";
            }
        });

    });
</script>

<!--modal para marcar como leido-->
<script>
    document.addEventListener("DOMContentLoaded", ()=>{
        const cards = document.querySelectorAll(".cardNotificacion");
        cards.forEach(card => {
            card.addEventListener("click", ()=>{
                const id = card.getAttribute("data-id");
                fetch("../../view/cliente/marcar_notificacion.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "id=" + id
                })
                .then(res => res.text())
                .then(data => {

                    if(data.trim() === "ok"){
                        card.classList.remove("noLeida");
                        card.classList.add("leida");
                        const badge = document.querySelector(".numberNotif");

                        if(badge){
                            let num = parseInt(badge.innerText);
                            num--;

                            if(num <= 0){
                                badge.remove();
                            }else{
                                badge.innerText = num;
                            }
                        }
                    }
                });
            });
        });
    });
</script>