<?php
// file: view/users/register.php
$view = ViewManager::getInstance();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="../css/register.css">
    <title><?= i18n("Register Form") ?></title>
</head>
<html>
    
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

</html>