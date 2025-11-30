<?php
//file: view/projects/index.php
require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();

$projects = $view->getVariable("projects");
$currentuserName = $view->getVariable("currentusername");
$currentuserMail = $view->getVariable("currentusermail");
?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" type="text/css" href=./view/css/projects/index.css>
    <title><?= i18n("Dashboard") ?></title>
</head>

<body>
    <header>
        <div>
            <div class="username" id="userDisplay"><?= $currentuserName ?></div>
            <div class="small" id="userEmail"><?= $currentuserMail ?></div>
        </div>
        <div>
            <div class="lang-switch">
                <a href="index.php?controller=language&amp;action=change&amp;lang=en" class="lang-btn">ES</a>
                <a href="index.php?controller=language&amp;action=change&amp;lang=en" class="lang-btn">EN</a>
            </div>

            <form action="index.php?action=index" method="get">
                <button type="button" class="add-btn"><?= i18n("Cerrar sesion") ?></button>
            </form>
        </div>
    </header>

    <section class="project-list">
        <div class="add-project-container">
            <a href="index.php?controller=projects&amp;action=add">
                <button id="openNewProjectModal" class="add-btn"><?= i18n("Añadir Proyecto") ?></button>
            </a>
        </div>

        <div class="content-center">
            <div class="table-container">
                <table id="projectsTable">
                    <thead>
                        <tr>
                            <th><?= i18n("Nombre del Proyecto") ?></th>
                            <th><?= i18n("Tareas ToDo") ?></th>
                            <th><?= i18n("Tareas Working") ?></th>
                            <th><?= i18n("Tareas Done") ?></th>
                        </tr>
                    </thead>
                    <tbody id="projectsContainer">

                        <?php foreach ($projects as $project): ?>
                            <tr>
                                <td><a class="project-link"
                                        href="index.php?controller=projects&amp;action=view&amp;id=<?= $project->getId() ?>">
                                        <?= $project->getName() ?></a>
                                </td>
                                <td><span class="task-count"><?= $project->getTaskNumberByType("ToDo") ?></span></td>
                                <td><span class="task-count"><?= $project->getTaskNumberByType("Working") ?></span></td>
                                <td><span class="task-count"><?= $project->getTaskNumberByType("Done") ?></span></td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </section>
</body>

</html>