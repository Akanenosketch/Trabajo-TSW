<?php
// file: view/users/register.php
$view = ViewManager::getInstance();
$errors = $view->getVariable("errors");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="./view/css/users/register.css">
    <title><?= i18n("Register Form") ?></title>
</head>
<html>

<div id="registerOverlay" class="overlay">
    <div class="modal">
        <header>
            <h2 id="registerTitle"><?= i18n("Registrarse") ?></h2>
        </header>

        <form id="registerForm" method="post">
            <div class="form-row">
                <label for="regNombre"><?= i18n("Nombre de Usuario") ?></label>
                <input id="regNombre" name="nombreUsuario" type="text" placeholder="<?= i18n("Nombre de Usuario") ?>"
                    required minlength="4" />
            </div>

            <div class="form-row">
                <label for="regCorreo"><?= i18n("Correo") ?></label>
                <input id="regCorreo" name="correo" type="email" placeholder="ivan.martinez.estevez@uvigo.es" required
                    pattern="^[a-zA-Z0-9.]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,}$" />
            </div>

            <div class="form-row">
                <label for="regPass"><?= i18n("Contraseña") ?></label>
                <input id="regPass" name="contrasena" type="password" placeholder="<?= i18n("Contraseña") ?>" required
                    minlength="6" />
            </div>

            <div class="form-actions">
                <a href="index.php?action=index">
                    <button type="button" class="secondary"><?= i18n("Cancelar") ?></button>
                </a>
                <button type="submit" class="primary"
                    formaction="index.php?action=register"><?= i18n("Crear Cuenta") ?></button>
            </div>
        </form>
    </div>
</div>

<?php if (!is_null($errors)): ?>
    <?php foreach ($errors as $error): ?>
        <script>
            var errorMsg = <?php echo json_encode($error) ?>
            console.log(errorMsg);
            alert(errorMsg);
        </script>
    <?php endforeach; ?>
<?php endif ?>


</html>