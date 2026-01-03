<?php
//file: view/projects/index.php
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
    <link rel="stylesheet" type="text/css" href="./view/css/projects/index.css">
    <title><?= i18n("Dashboard") ?></title>
</head>

<body>
    <div class="lang-switch">
        <a href="index.php?controller=language&amp;action=change&amp;lang=es" class="lang-btn">ES</a>
        <a href="index.php?controller=language&amp;action=change&amp;lang=en" class="lang-btn">EN</a>
    </div>
    
    <header>
        <div>
            <div class="username" id="userDisplay"><?= $currentuserName ?></div>
        </div>
        <div>
            <!--Mejorar esto y meter en el CSS-->
            <a href="index.php?action=edit">
                <button type="button" class="add-btn"><?= i18n("Editar Usuario") ?></button>
            </a>

            <a href="index.php?action=logout">
                <button type="button" class="add-btn"><?= i18n("Cerrar sesion") ?></button>
            </a>
        </div>
    </header>

    <section class="project-list">


        <div class="content-center">
            <div class="table-container">
                <table id="projectsTable">
                    <thead>
                        <tr>
                            <th><?= i18n("Nombre del Proyecto") ?></th>
                            <th><?= i18n("Tareas Pendientes") ?></th>
                            <th><?= i18n("Tareas En Proceso") ?></th>
                            <th><?= i18n("Tareas Completadas") ?></th>
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
                        <div class="add-project-container">
            <a href="index.php?controller=projects&amp;action=add">
                <button id="openNewProjectModal" class="add-btn"><?= i18n("Añadir Proyecto") ?></button>
            </a>
        </div>
            </div>
        </div>
    </section>
</body>

</html>