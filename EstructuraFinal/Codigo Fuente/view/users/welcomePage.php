<?php
// file: view/users/login.php
$view = ViewManager::getInstance();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="../css/welcomePage.css">
    <title><?= i18n("Pagina de Bienvenida")?></title>
</head>

<body>
     <main class="container">
        <div class="welcome-card" role="region" aria-labelledby="bienvenido">
            <h1 id="bienvenido"><?= i18n("Bienvenido")?></h1>
            <p class="subtitle"><?= i18n("Una app de administracion al estilo Kanban")?></p>

            <div class="actions">
                <button id="openLogin" class="primary" type="button"><?= i18n("Iniciar sesion")?></button>
                <button id="openRegister" class="secondary" type="button"><?= i18n("Registrarse")?></button>
            </div>
            </p>
        </div>
    </main>




    <div id="registerOverlay" class="overlay">
        <div class="modal">
            <header>
                <h2 id="registerTitle"><?= i18n("Registrarse")?></h2>
                <button class="close-btn" data-close="registerOverlay" aria-label="Cerrar">&times;</button>
            </header>

            <form id="registerForm" novalidate>
                <div class="form-row">
                    <label for="regNombre"><?= i18n("Nombre de Usuario")?></label>
                    <input id="regNombre" name="nombreUsuario" type="text" placeholder="<?= i18n("Nombre de Usuario")?>" required />
                </div>

                <div class="form-row">
                    <label for="regCorreo"><?= i18n("Correo")?></label>
                    <input id="regCorreo" name="correo" type="email" placeholder="ivan.martinez.estevez@uvigo.es" required />
                </div>

                <div class="form-row">
                    <label for="regPass"><?= i18n("Contraseña")?></label>
                    <input id="regPass" name="contrasena" type="password" placeholder="<?= i18n("Contraseña")?>" required
                        minlength="6" />
                </div>

                <div id="regError" class="error" role="alert" style="display:none"></div>

                <div class="form-actions">
                    <button type="button" class="secondary" data-close="registerOverlay"><?= i18n("Cancelar")?></button>
                    <button type="submit" class="primary"><?= i18n("Crear Cuenta")?></button>
                </div>
            </form>
        </div>
    </div>

    <footer class="site-footer">
        <div><strong><?= i18n("Informacion:")?></strong> <?= i18n("Proyecto Trabajo-TSW — Interfaz de Ejemplo.")?></div>
        <div class="small"><?= i18n("Contacto: equipo@example.com · Version 1.0")?></div>
    </footer>

</body>

</html>