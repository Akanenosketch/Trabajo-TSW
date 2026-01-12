<?php
//file: view/projects/index.php
$view = ViewManager::getInstance();

$projects = $view->getVariable("projects");
$currentuserName = $view->getVariable("currentusername");
?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="./view/css/theme.css">
    <link rel="stylesheet" type="text/css" href="./view/css/unifiedThemes.css">
    <title><?= i18n("Dashboard") ?></title>
</head>

<body class="Ibody">
    <div class="lang-switch">
        <a href="index.php?controller=language&amp;action=change&amp;lang=es" class="lang-btn">ES</a>
        <a href="index.php?controller=language&amp;action=change&amp;lang=en" class="lang-btn">EN</a>
    </div>
    <button id="themeToggle" class="theme-toggle global" aria-label="<?= i18n("Alternar tema") ?>">🌙</button>
    <header class="Iheader">
        <div>
            <div class="username" id="userDisplay"><?= $currentuserName ?></div>
        </div>
        <div>
            <a href="index.php?controller=categories&amp;action=index">
                <button type="button" class="Iadd-btn"><?= i18n("Listar Categorias") ?></button>
            </a>
            <a href="index.php?action=edit">
                <button type="button" class="Iadd-btn"><?= i18n("Editar Usuario") ?></button>
            </a>

            <a href="index.php?action=logout">
                <button type="button" class="Iadd-btn"><?= i18n("Cerrar sesion") ?></button>
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
                        <button id="openNewProjectModal" class="Iadd-btn"><?= i18n("Añadir Proyecto") ?></button>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <script src="./view/js/theme.js"></script>
</body>

</html>