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
            <div class="bodyGestor">
                <?php include_once('../../src/partials/welcome.php')?>

                <h3 class="mt-3">Dashboard</h3>

                dashboard <br>
                Total clientes activos — Card <br>

                Clientes morosos — Card <br>

                Ingresos del mes — Card <br>

                Pedidos del día — Card <br>

                Resumen visual de ingresos — Gráfico pequeño <br>

                Últimos 5 movimientos del sistema — Mini lista <br>
            </div>
        </section>


        

        <footer>
            footer
        </footer>
        
    </section>

    
</body>
</html>
