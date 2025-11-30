<?php
// file: view/users/login.php
$view = ViewManager::getInstance();
$errors = $view->getVariable("errors");
$project = $view->getVariable("project");
$users = $view->getVariable("users");
$currentuserMail = $view->getVariable("currentusermail");
$projectUsers = $view->getVariable("projectUsers");
?>

<!doctype html>
<html>

<body>
    <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" type="text/css" href=./view/css/projects/form.css>
    <title><?= i18n("Formulario de Projectos") ?></title>
</head>
    <div id="newProjectModal" class="modal-overlay" aria-hidden="true">
        <div class="modal-box">
            <h3><?= i18n("Dashboard") ?>Nuevo Proyecto</h3>
            <form id="addProjectForm">
                <div class="form-row">
                    <label><?= i18n("Dashboard") ?>Nombre del Proyecto</label>
                    <input id="newProjectName" type="text" required>
                </div>
                <div class="form-row">
                    <label><?= i18n("Dashboard") ?>Participantes</label>
                    <div id="initialUsers" class="checkbox-list"></div>
                </div>
                <!--ESTE STYLE AL CSS-->
                <div style="text-align:right;margin-top:12px">
                    <button type="button" class="btn" id="cancelProjectBtn"><?= i18n("Dashboard") ?>Cancelar</button>
                    <button type="submit" class="btn primary"><?= i18n("Dashboard") ?>Crear proyecto</button>
                </div>
            </form>
        </div>
    </div>


</body>
</html>