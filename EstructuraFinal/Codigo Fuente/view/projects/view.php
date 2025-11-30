<?php
// file: view/projects/view.php
$view = ViewManager::getInstance();
$project = $view->getVariable("project");
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="./view/css/projects/view.css">
    <title><?= i18n("Proyecto") ?></title>
</head>

<body>
    <header>
        <div class="header-content">
            <div class="header-left">
                <div class="project-title" id="projName"><?= $project->getName() ?></div>
                <div class="stats-container">
                    <div class="stat-item">
                        <span id="participantsCount"><?= count($project->getUsers()) ?></span>
                        <span class="stat-label"><?= i18n("Participantes") ?></span>
                    </div>
                    <div class="stat-item">
                        <span id="totalTasks"><?= count($project->getTasks()) ?></span>
                        <span class="stat-label"><?= i18n("Tareas Totales") ?></span>
                    </div>
                    <div class="stat-item">
                        <span id="completionRate"><?= $project->getCompletedPercent() ?>%</span>
                        <span class="stat-label"><?= i18n("Completado") ?></span>
                    </div>
                </div>
            </div>
            <div class="controls">
                <div class="lang-switch">
                    <a href="index.php?controller=language&amp;action=change&amp;lang=en" class="lang-btn">ES</a>
                    <a href="index.php?controller=language&amp;action=change&amp;lang=en" class="lang-btn">EN</a>
                </div>
                <a href="index.php?controller=projects&amp;action=index">
                    <button class="btn primary"><?= i18n("Volver") ?></button>
                </a>
            </div>
        </div>
    </header>

    <main class="project-content">
        <div class="tasks-container">
            <div class="cols">
                <div class="col">
                    <h4><?= i18n("ToDo") ?></h4>
                    <div id="col-todo">
                        <?php foreach ($project->getTasks() as $task): ?>
                            <?php if (strcmp($task->getStatus(), "ToDo") == 0): ?>
                                <div class="task">
                                    <span><?= $task->getName() ?>
                                        <?php foreach ($task->getUsers() as $user): ?>
                                            <span class="small"> - <?= $user->getUserName() ?></span>
                                        <?php endforeach; ?>
                                    </span>
                                    <div>
                                        <a
                                            href="index.php?controller=tasks&amp;action=view&amp;id=<?= $project->getId() ?>&amp;task_id=<?= $task->getId() ?>">
                                            <button class="btn"><?= i18n("Ver") ?></button>
                                        </a>
                                        <a
                                            href="index.php?controller=tasks&amp;action=edit&amp;id=<?= $project->getId() ?>&amp;task_id=<?= $task->getId() ?>">
                                            <button class="btn"><?= i18n("Editar") ?></button>
                                        </a>
                                        <a
                                            href="index.php?controller=tasks&amp;action=edit&amp;id=<?= $project->getId() ?>&amp;task_id=<?= $task->getId() ?>">
                                            <button class="btn"><?= i18n("Editar") ?></button>
                                        </a>
                                        <form
                                            action="index.php?controller=tasks&amp;action=delete&amp;id=<?= $project->getId() ?>&amp;task_id=<?= $task->getId() ?>">
                                            <button class="btn danger" type="submit"><?= i18n("Borrar") ?></button>
                                        </form>
                                    </div>
                                </div>
                            <?php endif ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col">
                    <h4><?= i18n("Working") ?></h4>
                    <div id="col-working">
                        <?php foreach ($project->getTasks() as $task): ?>
                            <?php if (strcmp($task->getStatus(), "Working") == 0): ?>
                                <div class="task">
                                    <span><?= $task->getName() ?>
                                        <?php foreach ($task->getUsers() as $user): ?>
                                            <span class="small"> - <?= $user->getUserName() ?></span>
                                        <?php endforeach; ?>
                                    </span>
                                    <div>
                                        <a
                                            href="index.php?controller=tasks&amp;action=view&amp;id=<?= $project->getId() ?>&amp;task_id=<?= $task->getId() ?>">
                                            <button class="btn"><?= i18n("Ver") ?></button>
                                        </a>
                                        <a
                                            href="index.php?controller=tasks&amp;action=edit&amp;id=<?= $project->getId() ?>&amp;task_id=<?= $task->getId() ?>">
                                            <button class="btn"><?= i18n("Editar") ?></button>
                                        </a>
                                        <a
                                            href="index.php?controller=tasks&amp;action=edit&amp;id=<?= $project->getId() ?>&amp;task_id=<?= $task->getId() ?>">
                                            <button class="btn"><?= i18n("Editar") ?></button>
                                        </a>
                                        <form
                                            action="index.php?controller=tasks&amp;action=delete&amp;id=<?= $project->getId() ?>&amp;task_id=<?= $task->getId() ?>">
                                            <button class="btn danger" type="submit"><?= i18n("Borrar") ?></button>
                                        </form>
                                    </div>
                                </div>
                            <?php endif ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col">
                    <h4><?= i18n("Done") ?></h4>
                    <div id="col-done">
                        <?php foreach ($project->getTasks() as $task): ?>
                            <?php if (strcmp($task->getStatus(), "Working") == 0): ?>
                                <div class="task">
                                    <span><?= $task->getName() ?>
                                        <?php foreach ($task->getUsers() as $user): ?>
                                            <span class="small"> - <?= $user->getUserName() ?></span>
                                        <?php endforeach; ?>
                                    </span>
                                    <div>
                                        <a
                                            href="index.php?controller=tasks&amp;action=view&amp;id=<?= $project->getId() ?>&amp;task_id=<?= $task->getId() ?>">
                                            <button class="btn"><?= i18n("Ver") ?></button>
                                        </a>
                                        <a
                                            href="index.php?controller=tasks&amp;action=edit&amp;id=<?= $project->getId() ?>&amp;task_id=<?= $task->getId() ?>">
                                            <button class="btn"><?= i18n("Editar") ?></button>
                                        </a>
                                        <a
                                            href="index.php?controller=tasks&amp;action=edit&amp;id=<?= $project->getId() ?>&amp;task_id=<?= $task->getId() ?>">
                                            <button class="btn"><?= i18n("Editar") ?></button>
                                        </a>
                                        <form
                                            action="index.php?controller=tasks&amp;action=delete&amp;id=<?= $project->getId() ?>&amp;task_id=<?= $task->getId() ?>">
                                            <button class="btn danger" type="submit"><?= i18n("Borrar") ?></button>
                                        </form>
                                    </div>
                                </div>
                            <?php endif ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="task-actions">
            <a href="index.php?controller=tasks&amp;action=add&amp;id=<?= $project->getId() ?>">
                <button class="btn primary action-btn" id="openTaskModal"><?= i18n("+ Nueva Tarea") ?></button>
            </a>
            <a href="index.php?controller=projects&amp;action=edit&amp;id=<?= $project->getId() ?>">
                <button class="btn action-btn" id="editProjBtn"
                    style="margin-top:12px"><?= i18n("Editar Proyecto") ?></button>
            </a>

            <form action="index.php?controller=projects&amp;action=delete&amp;id=<?= $project->getId() ?>">
                <button class="btn danger action-btn" id="deleteProjBtn"
                    type="submit"><?= i18n("Eliminar Proyecto") ?></button>
            </form>
        </div>
    </main>
</body>

</html>