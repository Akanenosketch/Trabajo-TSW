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
    <title><?= i18n("Participantes") ?>Proyecto</title>


</head>

<body>
    <header>
        <div class="header-content">
            <div class="header-left">
                <div class="project-title" id="projName"><?= i18n("Participantes") ?>Proyecto</div>
                <div class="stats-container">
                    <div class="stat-item">
                        <span id="participantsCount">0</span>
                        <button class="btn-link" id="openUserModal"><?= i18n("Participantes") ?>Participantes</button>
                    </div>
                    <div class="stat-item">
                        <span id="totalTasks">ALGO PHP  0</span>
                        <span class="stat-label"><?= i18n("Participantes") ?>Tareas Totales</span>
                    </div>
                    <div class="stat-item">
                        <span id="completionRate">ALGO PHP  0%</span>
                        <span class="stat-label"><?= i18n("Participantes") ?>Completado</span>
                    </div>
                </div>
            </div>
            <div class="controls">
                <button class="btn primary"><?= i18n("Participantes") ?>Volver</button>
            </div>
        </div>
    </header>

    <main class="project-content">
        <div class="tasks-container">
            <div class="cols">
                <div class="col">
                    <h4><?= i18n("Participantes") ?>ToDo</h4>
                    <div id="col-todo"></div>
                </div>
                <div class="col">
                    <h4><?= i18n("Participantes") ?>Working</h4>
                    <div id="col-working"></div>
                </div>
                <div class="col">
                    <h4><?= i18n("Participantes") ?>Done</h4>
                    <div id="col-done"></div>
                </div>
            </div>
        </div>
        <div class="task-actions">
            <button class="btn primary action-btn" id="openTaskModal"><?= i18n("Participantes") ?> + Nueva Tarea</button>
            <button class="btn action-btn" id="editProjBtn" style="margin-top:12px"><?= i18n("Participantes") ?>Editar Proyecto</button>
            <button class="btn danger action-btn" id="deleteProjBtn" style="margin-top:8px"><?= i18n("Participantes") ?>Eliminar Proyecto</button>
        </div>
    </main>
</body>

</html>