<?php
$sql = "SELECT logo FROM clientes WHERE id = ?";
    $stmt = $bd->prepare($sql);
    $stmt->execute([$_SESSION['cliente_id']]);
    $clienteLogo = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<div class="logoSidebar">
    <?php if($_SESSION['rol'] == "admin"): ?>
        <img src="../../src/img/logo_admin/2_sf.png" alt=" foto logo admin">
    <?php elseif($_SESSION['rol'] == "cliente"): ?>
        <?php if(!empty($clienteLogo['logo'])): ?>
            <img src="../../<?= htmlspecialchars($clienteLogo['logo']) ?>" alt="logo cliente">
        <?php else: ?>
            <div>Cliente sin logo</div>
        <?php endif; ?>
    <?php else: ?>
        <div>no hay foto</div>
    <?php endif ?>
</div>