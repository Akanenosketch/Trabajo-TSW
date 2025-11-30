<?php
// file: view/users/login.php
$view = ViewManager::getInstance();
$errors = $view->getVariable("errors");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="./view/css/users/login.css">
    <title><?= i18n("Login Form") ?></title>
</head>
<body>
<div id="loginOverlay" class="overlay">
    <div class="modal">
        <header>
            <h2 id="loginTitle"><?= i18n("Iniciar sesion") ?></h2>
        </header>

        <form id="loginForm">
            <div class="form-row">
                <label for="loginNombre"><?= i18n(key: "Correo") ?></label>
                <input id="loginNombre" name="correo" type="email" required
                    pattern="^[a-zA-Z0-9.]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,}$"
                    placeholder="<?= i18n("Escribe tu correo") ?>" />
            </div>

            <div class="form-row">
                <label for="loginPass"><?= i18n("Contraseña") ?></label>
                <input id="loginPass" name="contrasena" type="password" required minlength="6"
                    placeholder="<?= i18n("Contraseña") ?>" />
            </div>

            <div class="form-actions">
                <a href="index.php?action=index">
                    <button type="button" class="secondary"><?= i18n("Cancelar") ?></button>
                </a> <button type="submit" class="primary" formaction="index.php?action=login"
                    formmethod="post"><?= i18n("Entrar") ?></button>
            </div>
        </form>
    </div>
</div>
    
</body>

<?php if (!is_null($errors)): ?>
    <?php foreach ($errors as $error): ?>
        <script>
            var errorMsg = <?php echo json_encode($error) ?>;
            console.log(errorMsg);
            alert(errorMsg);
        </script>
    <?php endforeach; ?>
<?php endif ?>

</html>