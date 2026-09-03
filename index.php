<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SUFI PERU | Gestión y Seguimiento de Pedidos</title>
    <meta name="Author" lang="es" content="G Soft Studio">
    <meta name="keywords" content="Gestor de pedidos">
    <meta name="description" content="SUFI es una plataforma para negocios con delivery que permite registrar pedidos, controlar estados y compartir un enlace de seguimiento con sus clientes.">
    <meta name="keywords" content="gestión de pedidos, delivery, seguimiento de pedidos, emprendedores Perú, sistema para delivery, pedidos online">
    <meta name="copyright" content="" />
    <meta name="robots" content="index, follow">
    <meta name="robots" content="noindex, nofollow">

    <link rel="icon" href="../../src/img/logo_admin/3.1_sf.ico">

    <!-- Open Graph -->
    <meta property="og:title"
    content="SUFI Perú - Gestión y Seguimiento de Pedidos">

    <meta property="og:description"
    content="Controla tus pedidos y comparte un link de seguimiento con tus clientes.">

    <meta property="og:image"
    content="https://sufiperu.com/src/img/banner-sufi.jpg">

    <meta property="og:url"
    content="https://sufiperu.com">

    <meta property="og:type"
    content="website">


    <!--css-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="./src/css/style.css?v=<?= time() ?>">

    <!--prefetch-->



    <!--iconos iconify-->
    <script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>

    <!--iconos boostrap-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    
</head>

<body>

<!-- ===============NAVBAR================================ -->

<header class="sufi-navbar">
    <div class="sufi-nav-container">
        <a href="#inicio" class="logoHome">
            <img src="./src/img/logo_admin/6_sinmargen_sf.png" alt="foto de proveedor" >
        </a>

        <nav class="sufi-nav-menu">
            <a href="#inicio">Inicio</a>
            <a href="#caracteristicas">Características</a>
            <a href="#demo">Demo</a>
            <a href="#precio">Precio</a>
            <a href="#contacto">Contacto</a>
        </nav>

        <a href="login.php" class="sufi-btn sufi-btn-login">
            Iniciar sesión
        </a>

        <button class="sufi-menu-mobile" id="sufiMenuBtn">
            <iconify-icon icon="mdi:menu"></iconify-icon>
        </button>

    </div>
</header>


<!-- ===============HERO============================= -->

<section class="sufi-hero" id="inicio">
    <div class="sufi-hero-container">
        <div class="sufi-hero-content">

            <span class="sufi-hero-badge">
                <iconify-icon icon="mdi:sparkles"></iconify-icon>
                Digitaliza tu negocio
            </span>

            <h1>
                Gestiona tu negocio
                <span>de forma fácil y organizada.</span>
            </h1>

            <p>
                Registra y administra tus pedidos, consulta tus ventas,
                comparte enlaces de seguimiento y mantén toda la información
                de tu negocio en un solo lugar.
            </p>

            <div class="sufi-hero-price">
                <span>Desde</span>
                <strong>S/20</strong>
                <span>al mes</span>
            </div>

            <div class="sufi-hero-buttons">
                <a href="#demo" class="sufi-btn sufi-btn-primary">
                    Probar SUFI
                    <iconify-icon icon="mdi:arrow-right"></iconify-icon>
                </a>
                <a href="#contacto" class="sufi-btn sufi-btn-secondary">
                    Quiero más información
                </a>
            </div>

            <div class="sufi-hero-benefits">
                <div>
                    <iconify-icon icon="mdi:check-circle"></iconify-icon>
                    Sin instalación
                </div>
                <div>
                    <iconify-icon icon="mdi:check-circle"></iconify-icon>
                    Fácil de usar
                </div>
                <div>
                    <iconify-icon icon="mdi:check-circle"></iconify-icon>
                    Acceso desde cualquier dispositivo
                </div>
            </div>
        </div>


        <!-- Mockup dashboard -->

        <div class="sufi-hero-preview">
            <div class="sufi-preview-window">
                <div class="sufi-preview-header">
                    <div class="sufi-preview-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <span>SUFI</span>
                </div>
                <div class="sufi-preview-body">
                    <div class="sufi-preview-sidebar">
                        <div class="sufi-preview-logo">
                            SUFI
                        </div>
                        <div class="sufi-preview-menu active"></div>
                        <div class="sufi-preview-menu"></div>
                        <div class="sufi-preview-menu"></div>
                        <div class="sufi-preview-menu"></div>
                        <div class="sufi-preview-menu"></div>
                    </div>

                    <div class="sufi-preview-dashboard">
                        <h3>Dashboard</h3>
                        <div class="sufi-preview-cards">
                            <div class="sufi-preview-card">
                                <small>Pedidos</small>
                                <strong>128</strong>
                            </div>
                            <div class="sufi-preview-card">
                                <small>Ingresos</small>
                                <strong>S/ 4,250</strong>
                            </div>
                            <div class="sufi-preview-card">
                                <small>Entregados</small>
                                <strong>96</strong>
                            </div>
                        </div>
                        <div class="sufi-preview-chart">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- =============CONFIANZA============================ -->

<section class="sufi-trust">
    <div class="sufi-trust-container">
        <p>
            Digitalizar tu negocio no tiene por qué ser complicado.
        </p>
        <div class="sufi-trust-items">
            <div>
                <iconify-icon icon="mdi:monitor-dashboard"></iconify-icon>
                <span>Todo organizado</span>
            </div>
            <div>
                <iconify-icon icon="mdi:web"></iconify-icon>
                <span>Sin instalar programas</span>
            </div>
            <div>
                <iconify-icon icon="mdi:cellphone-link"></iconify-icon>
                <span>Desde cualquier dispositivo</span>
            </div>
            <div>
                <iconify-icon icon="mdi:cash-multiple"></iconify-icon>
                <span>Precio accesible</span>
            </div>
        </div>
    </div>
</section>


<!-- ================CARACTERÍSTICAS================================ -->

<section class="sufi-features" id="caracteristicas">
    <div class="sufi-section-container">
        <div class="sufi-section-heading">
            <span class="sufi-section-label">
                ¿Qué puedes hacer con SUFI?
            </span>
            <h2>
                Todo lo necesario para
                <span>organizar tus pedidos.</span>
            </h2>
            <p>
                SUFI te ayuda a dejar atrás los registros manuales
                y tener una mejor visión de lo que ocurre en tu negocio.
            </p>
        </div>

        <div class="sufi-features-grid">
            <article class="sufi-feature-card">
                <div class="sufi-feature-icon">
                    <iconify-icon icon="mdi:package-variant-closed"></iconify-icon>
                </div>
                <h3>Gestión de pedidos</h3>
                <p>
                    Registra, consulta y actualiza tus pedidos
                    desde un solo lugar.
                </p>
            </article>

            <article class="sufi-feature-card">
                <div class="sufi-feature-icon">
                    <iconify-icon icon="mdi:account-group"></iconify-icon>
                </div>
                <h3>Clientes</h3>
                <p>
                    Mantén organizada la información relacionada
                    con tus clientes y pedidos.
                </p>
            </article>

            <article class="sufi-feature-card">
                <div class="sufi-feature-icon">
                    <iconify-icon icon="mdi:chart-line"></iconify-icon>
                </div>
                <h3>Dashboard</h3>
                <p>
                    Visualiza información importante de tu negocio
                    mediante indicadores y estadísticas.
                </p>
            </article>

            <article class="sufi-feature-card">
                <div class="sufi-feature-icon">
                    <iconify-icon icon="mdi:cash-multiple"></iconify-icon>
                </div>
                <h3>Finanzas</h3>
                <p>
                    Consulta tus ingresos, ventas potenciales
                    y pedidos cancelados.
                </p>
            </article>


            <article class="sufi-feature-card">
                <div class="sufi-feature-icon">
                    <iconify-icon icon="mdi:link-variant"></iconify-icon>
                </div>
                <h3>Seguimiento</h3>
                <p>
                    Comparte un enlace para que tus clientes
                    puedan consultar el estado de su pedido.
                </p>
            </article>

            <article class="sufi-feature-card">
                <div class="sufi-feature-icon">
                    <iconify-icon icon="mdi:file-export-outline"></iconify-icon>
                </div>
                <h3>Exportación</h3>
                <p>
                    Exporta la información de tus pedidos
                    para trabajar con ella cuando lo necesites.
                </p>
            </article>
        </div>
    </div>
</section>


<!-- ====================PARA QUIÉN====================================== -->

<section class="sufi-target">
    <div class="sufi-target-container">
        <div class="sufi-target-content">
            <span class="sufi-section-label">
                Hecho para negocios reales
            </span>
            <h2>
                Empieza a digitalizar
                <span>tu negocio hoy.</span>
            </h2>
            <p>
                No importa si estás comenzando o si tu negocio ya tiene
                varios pedidos al día. SUFI está pensado para ayudarte
                a organizar mejor tu operación.
            </p>
            <ul class="sufi-check-list">
                <li>
                    <iconify-icon icon="mdi:check-circle"></iconify-icon>
                    Emprendimientos
                </li>
                <li>
                    <iconify-icon icon="mdi:check-circle"></iconify-icon>
                    Negocios en crecimiento
                </li>
                <li>
                    <iconify-icon icon="mdi:check-circle"></iconify-icon>
                    Negocios que aún trabajan con registros manuales
                </li>
                <li>
                    <iconify-icon icon="mdi:check-circle"></iconify-icon>
                    Negocios que buscan centralizar sus pedidos
                </li>
            </ul>
        </div>

        <div class="sufi-target-message">
            <div class="sufi-target-message-icon">
                <iconify-icon icon="mdi:lightbulb-on-outline"></iconify-icon>
            </div>
            <h3>
                Digitalizar nunca fue tan fácil.
            </h3>
            <p>
                No necesitas conocimientos técnicos ni instalar
                programas complicados.
            </p>
        </div>
    </div>
</section>

<!-- ===============DEMO=========================== -->

<section class="sufi-demo" id="demo">
    <div class="sufi-demo-container">
        <div class="sufi-demo-content">
            <span class="sufi-section-label">
                Conoce SUFI
            </span>
            <h2>
                Pruébalo ahora
                <span>sin compromiso.</span>
            </h2>
            <p>
                ¿Quieres conocer cómo funciona antes de contratar?
                Puedes utilizar nuestro usuario demo y explorar
                las principales funciones de SUFI.
            </p>
            <div class="sufi-demo-note">
                <iconify-icon icon="mdi:information-outline"></iconify-icon>
                <span>
                    El usuario demo es únicamente para conocer
                    el funcionamiento del sistema.
                </span>
            </div>

        </div>


        <div class="sufi-demo-card">
            <div class="sufi-demo-card-header">
                <div class="sufi-demo-card-icon">
                    <iconify-icon icon="mdi:account-circle-outline"></iconify-icon>
                </div>
                <div>
                    <h3>Usuario Demo</h3>
                    <span>Acceso de prueba</span>
                </div>
            </div>

            <div class="sufi-demo-credentials">
                <div class="sufi-credential">
                    <label>Usuario</label>
                    <div class="sufi-credential-value">
                        <span id="sufiDemoUser">geeea677</span>
                        <button type="button" onclick="copiarTexto('sufiDemoUser')" title="Copiar usuario">
                            <iconify-icon icon="mdi:content-copy"></iconify-icon>
                        </button>
                    </div>
                </div>

                <div class="sufi-credential">
                    <label>Contraseña</label>
                    <div class="sufi-credential-value">
                        <span id="sufiDemoPassword"> demodemo </span>
                        <button type="button" onclick="copiarTexto('sufiDemoPassword')" title="Copiar contraseña">
                            <iconify-icon icon="mdi:content-copy"></iconify-icon>
                        </button>
                    </div>
                </div>
            </div>

            <a href="login.php" class="sufi-btn sufi-btn-primary sufi-btn-demo">
                Ingresar al demo
                <iconify-icon icon="mdi:arrow-right"></iconify-icon>
            </a>
        </div>
    </div>
</section>


<!-- =====================================================
     PRUEBA 15 DÍAS
===================================================== -->

<section class="sufi-trial">

    <div class="sufi-trial-container">

        <div class="sufi-trial-content">

            <span class="sufi-trial-badge">
                SIN COMPROMISO
            </span>

            <h2>
                ¿Quieres probar SUFI
                con tu propio negocio?
            </h2>

            <p>
                Solicita <strong>15 días de prueba</strong> y utiliza SUFI
                con tu negocio antes de decidir si quieres contratar
                el plan mensual.
            </p>

            <div class="sufi-trial-points">

                <div>
                    <iconify-icon icon="mdi:check-circle"></iconify-icon>
                    15 días de prueba
                </div>

                <div>
                    <iconify-icon icon="mdi:check-circle"></iconify-icon>
                    Sin obligación de contratar
                </div>

                <div>
                    <iconify-icon icon="mdi:check-circle"></iconify-icon>
                    Conoce el sistema antes de decidir
                </div>

            </div>

            <a href="https://wa.me/51910263511?text=Hola,%20quiero%20solicitar%2015%20d%C3%ADas%20de%20prueba%20de%20SUFI."
                target="_blank" class="sufi-btn sufi-btn-whatsapp">
                <iconify-icon icon="mdi:whatsapp"></iconify-icon>
                Solicitar 15 días de prueba
            </a>
        </div>
    </div>
</section>


<!-- =====================================================
     PRECIO
===================================================== -->

<section class="sufi-pricing" id="precio">

    <div class="sufi-section-container">

        <div class="sufi-section-heading">

            <span class="sufi-section-label">
                Plan SUFI
            </span>

            <h2>
                Digitaliza tu negocio
                <span>desde S/20 al mes.</span>
            </h2>

            <p>
                Una solución accesible para comenzar a organizar
                la gestión de tu negocio.
            </p>

        </div>


        <div class="sufi-price-card">

            <div class="sufi-price-header">

                <span class="sufi-price-name">
                    SUFI
                </span>

                <div class="sufi-price">

                    <span>S/</span>
                    <strong>20</strong>
                    <small>/ mes</small>

                </div>

                <p>
                    Plan mensual
                </p>

            </div>


            <div class="sufi-price-divider"></div>


            <ul class="sufi-price-features">

                <li>
                    <iconify-icon icon="mdi:check"></iconify-icon>
                    Gestión de pedidos
                </li>

                <li>
                    <iconify-icon icon="mdi:check"></iconify-icon>
                    Dashboard
                </li>

                <li>
                    <iconify-icon icon="mdi:check"></iconify-icon>
                    Gestión de clientes
                </li>

                <li>
                    <iconify-icon icon="mdi:check"></iconify-icon>
                    Seguimiento de pedidos
                </li>

                <li>
                    <iconify-icon icon="mdi:check"></iconify-icon>
                    Módulo financiero
                </li>

                <li>
                    <iconify-icon icon="mdi:check"></iconify-icon>
                    Exportación de pedidos
                </li>

                <li>
                    <iconify-icon icon="mdi:check"></iconify-icon>
                    Acceso desde navegador
                </li>

            </ul>


            <a
                href="https://wa.me/TU_NUMERO?text=Hola,%20estoy%20interesado%20en%20SUFI."
                target="_blank"
                class="sufi-btn sufi-btn-primary sufi-btn-price">

                Quiero SUFI

            </a>

        </div>

    </div>

</section>


<!-- =====================================================
     CTA FINAL
===================================================== -->

<section class="sufi-final-cta" id="contacto">

    <div class="sufi-final-container">

        <span class="sufi-section-label">
            ¿Listo para comenzar?
        </span>

        <h2>
            Empieza a digitalizar
            <span>tu negocio.</span>
        </h2>

        <p>
            Conoce SUFI, pruébalo y decide cuándo dar el siguiente paso.
        </p>

        <div class="sufi-final-buttons">

            <a href="#demo" class="sufi-btn sufi-btn-primary">
                Probar demo
            </a>

            <a
                href="https://wa.me/TU_NUMERO?text=Hola,%20quiero%20conocer%20SUFI."
                target="_blank"
                class="sufi-btn sufi-btn-whatsapp">

                <iconify-icon icon="mdi:whatsapp"></iconify-icon>
                Hablar por WhatsApp

            </a>

        </div>

    </div>

</section>


<!-- =====================================================
     FOOTER
===================================================== -->

<footer class="sufi-footer">

    <div class="sufi-footer-container">

        <div class="sufi-footer-brand">

            <a href="#inicio" class="sufi-logo">
                SUFI
            </a>

            <p>
                Una forma simple de digitalizar
                y organizar tu negocio.
            </p>

        </div>


        <div class="sufi-footer-links">

            <h4>SUFI</h4>

            <a href="#inicio">Inicio</a>
            <a href="#caracteristicas">Características</a>
            <a href="#demo">Demo</a>
            <a href="#precio">Precio</a>

        </div>


        <div class="sufi-footer-links">

            <h4>Contacto</h4>

            <a
                href="https://wa.me/TU_NUMERO"
                target="_blank">

                WhatsApp

            </a>

            <a href="index.php">
                Iniciar sesión
            </a>

        </div>

    </div>


    <div class="sufi-footer-bottom">

        <p>
            © 2026 SUFI. Todos los derechos reservados.
        </p>

        <p>
            Una solución de G-Soft Studio
        </p>

    </div>

</footer>


<!-- =====================================================
     JAVASCRIPT
===================================================== -->

<script>

    // ==========================================
    // MENU MOBILE
    // ==========================================

    const sufiMenuBtn = document.getElementById("sufiMenuBtn");
    const sufiNavMenu = document.querySelector(".sufi-nav-menu");

    sufiMenuBtn.addEventListener("click", () => {

        sufiNavMenu.classList.toggle("sufi-nav-active");

    });


    // ==========================================
    // CERRAR MENU AL SELECCIONAR UNA OPCIÓN
    // ==========================================

    document.querySelectorAll(".sufi-nav-menu a").forEach(link => {

        link.addEventListener("click", () => {

            sufiNavMenu.classList.remove("sufi-nav-active");

        });

    });


    // ==========================================
    // COPIAR CREDENCIALES DEMO
    // ==========================================

    function copiarTexto(elementId) {

        const elemento = document.getElementById(elementId);

        navigator.clipboard.writeText(elemento.textContent.trim());

        const original = elemento.parentElement.querySelector("button").innerHTML;

        elemento.parentElement.querySelector("button").innerHTML =
            '<iconify-icon icon="mdi:check"></iconify-icon>';

        setTimeout(() => {

            elemento.parentElement.querySelector("button").innerHTML = original;

        }, 1500);

    }

</script>

</body>
</html>