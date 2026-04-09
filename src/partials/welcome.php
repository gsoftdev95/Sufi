
<div class="userWelcome">
    <h3>Bienvenido</h3>
    <h5>
    <?php
        if ($_SESSION['rol'] === 'cliente' && !empty($_SESSION['representante'])) {
            echo htmlspecialchars($_SESSION['representante']);
        } else {
            echo htmlspecialchars($_SESSION['username'] ?? 'Usuario');
        }
    ?>
    </h5>



</div>