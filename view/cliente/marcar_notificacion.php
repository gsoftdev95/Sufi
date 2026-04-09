<?php
require_once('../../controllers/functions.php');
require_once('../../src/partials/conexionBD.php');

if(!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'cliente'){
    exit;
}

if(isset($_POST['id'])){

    $id = (int) $_POST['id'];

    if(marcarNotificacionLeida($bd, $id)){
        echo "ok";
    }
}

?>