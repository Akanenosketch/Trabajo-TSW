<?php
// file: view/users/login.php
$view = ViewManager::getInstance();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="../css/login.css">
    <title><?= i18n("Login Form") ?></title>
</head>
<html>
    
<div id="loginOverlay" class="overlay">
    <div class="modal">
        <header>
            <h2 id="loginTitle"><?= i18n("Iniciar sesion") ?></h2>
            <button class="close-btn" data-close="loginOverlay" aria-label="Cerrar">&times;</button>
        </header>

        <form id="loginForm" novalidate>
            <div class="form-row">
                <label for="loginNombre"><?= i18n("Nombre de Usuario") ?></label>
                <input id="loginNombre" name="nombreUsuario" type="text"
                    placeholder="<?= i18n("Escribe tu nombre de usuario") ?>" />
            </div>

            <div class="form-row">
                <label for="loginPass"><?= i18n("Contraseña") ?></label>
                <input id="loginPass" name="contrasena" type="password" placeholder="<?= i18n("Contraseña") ?>" />
            </div>

            <div id="loginError" class="error" role="alert" style="display:none"></div>

            <div class="form-actions">
                <button type="button" class="secondary" data-close="loginOverlay"><?= i18n("Cancelar") ?></button>
                <button type="submit" class="primary"><?= i18n("Entrar") ?></button>
            </div>
        </form>
    </div>
</div>

</html>