<?php
function obtenerFechaFinSuscripcion($bd, $cliente_id){

    $sql = "SELECT fecha_fin 
            FROM clientes
            WHERE id = :cliente_id";

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
            <div class="estadoSuscripcion">

                <span class="iconUsers noti" id="iconEstado">
                    <i class="bi bi-check-circle"></i>
                </span>

                <div class="tooltipSuscripcion" id="tooltipSuscripcion">
                    Suscripción activa <br>
                    vence: <?= $fecha_fin ?? '---' ?>
                </div>

            </div>
        <?php endif; ?>

        <span class="iconUsers msg" id="iconNotificaciones">
            <i class="bi bi-chat-dots-fill"></i>
            <?php if($noLeidas > 0): ?>
                <span class="numberNotif"><?= $noLeidas ?></span>
            <?php endif; ?>
        </span>
        <a href="../../logout.php">
            <span class="iconUsers logout">
                <i class="bi bi-x-circle-fill"></i>
            </span>
        </a>
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



<script>
    const iconEstado = document.getElementById("iconEstado");
    const tooltip = document.getElementById("tooltipSuscripcion");

    if(iconEstado){
        iconEstado.addEventListener("click", function(){
            tooltip.style.display = 
                tooltip.style.display === "block" ? "none" : "block";
        });
    }
</script>

<!--script modal-->
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