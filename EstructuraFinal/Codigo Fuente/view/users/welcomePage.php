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

    <footer class="site-footer">
        <div><strong><?= i18n("Informacion:")?></strong> <?= i18n("Proyecto Trabajo-TSW — Interfaz de Ejemplo.")?></div>
        <div class="small"><?= i18n("Contacto: equipo@example.com · Version 1.0")?></div>
    </footer>

</body>

</html>