<?php
require_once('../../helpers/dd.php');
require_once('../../controllers/functions.php');
require_once('../../src/partials/conexionBD.php');

controlAcceso($bd, ['admin']);


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
            <div class="bodyCliente">
                Actividad <br>
                Registro de acciones del sistema — Tabla cronológica <br>

                Filtro por tipo de evento — Dropdown filtro <br>

                Filtro por fecha — Selector de fecha <br>

                Detalle de evento — Modal <br>
            </div>
        </section>

        <footer>
            footer
        </footer>
        
    </section>

    
</body>
</html>
