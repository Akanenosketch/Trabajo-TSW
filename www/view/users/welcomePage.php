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
    <link rel="stylesheet" type="text/css" href="./view/css/theme.css">
    <link rel="stylesheet" type="text/css" href="./view/css/users/welcomePage.css">
    <title><?= i18n("Pagina de Bienvenida") ?></title>
</head>

<body class="WPBody">
    <button id="themeToggle" class="theme-toggle global" aria-label="<?= i18n("Alternar tema") ?>">🌙</button>
    <main class="WPcontainer">

        <div class="lang-switch">
            <a href="index.php?controller=language&amp;action=change&amp;lang=es" class="lang-btn">ES</a>
            <a href="index.php?controller=language&amp;action=change&amp;lang=en" class="lang-btn">EN</a>
        </div>
        <div class="welcome-card" role="region" aria-labelledby="bienvenido">
            <h1 id="bienvenido"><?= i18n("Bienvenido") ?></h1>
            <p class="subtitle"><?= i18n("Una app de administracion al estilo Kanban") ?></p>
            <a href="index.php?action=login">
                <button id="openLogin" class="primary"><?= i18n("Iniciar sesion") ?></button>
            </a>
            <a href="index.php?action=register">
                <button id=" openRegister" class="secondary"><?= i18n("Registrarse") ?></button>
            </a>

            </p>
        </div>
    </main>

    <footer class="site-footer">
        <div><strong><?= i18n("Informacion:") ?></strong> <?= i18n("Proyecto Trabajo-TSW — Interfaz.") ?>
        </div>
        <div class="small"><?= i18n("Contacto: equipo@example.com · Version 1.0") ?></div>
    </footer>

    <script src="./view/js/theme.js"></script>
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