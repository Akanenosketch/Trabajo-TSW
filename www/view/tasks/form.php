<?php
// file: view/tasks/form.php
$view = ViewManager::getInstance();
$errors = $view->getVariable("errors");
$isViewing = $view->getVariable("isViewing", false);
$task = $view->getVariable("task");
$users = $view->getVariable("users");
$projectID = $view->getVariable("projectID");
$userNum = 1;
$taskUsers = array();
if ($task != null) $taskUsers = $task->getUsers();
?>


<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="./view/css/theme.css">
    <link rel="stylesheet" type="text/css" href="./view/css/unifiedThemes.css">
    <title><?= i18n("Formulario de Tareas") ?></title>
</head>

<body class="Fbody">

    <form action="index.php?controller=tasks&amp;action=<?php if (!is_null($task) && !$isViewing): ?>edit<?php endif ?><?php if (is_null($task)): ?>add<?php endif ?>" method="post">
        <div id="taskModal" class="Fmodal-overlay">

            <div class="Fmodal-box">
                <div class="Fmodal-content">
                    <h3 id="taskModalTitle">
                        <?php if (!is_null($task) && !$isViewing): ?><?= i18n("Editar Tarea") ?><?php endif ?><?php if (is_null($task)): ?><?= i18n("Añadir Tarea") ?><?php endif ?><?php if ($isViewing): ?><?= i18n("Datos de Tarea") ?><?php endif ?>
                    </h3>
                    <div class="form-row">
                        <label><?= i18n(key: "Nombre") ?></label>
                        <input id="modalTaskName" name="title" type="text" required minlength="1"
                            value="<?php if (!is_null($task)): ?><?= $task->getName() ?><?php endif ?>"
                            <?php if ($isViewing): ?>readonly<?php endif ?> />
                    </div>
                    <div class="form-row">
                        <label><?= i18n("Descripcion") ?></label>
                        <input id="modalTaskDesc" name="desc" type="text" required minlength="1"
                            value="<?php if (!is_null($task)): ?><?= $task->getDesc() ?><?php endif ?>"
                            <?php if ($isViewing): ?>readonly<?php endif ?> />
                    </div>
                    <div class="form-row">
                        <label><?= i18n("Fecha de Inicio") ?></label>
                        <input id="modalTaskBegin" name="beginDate" type="date" required
                            value="<?php if (!is_null($task)): ?><?= $task->getBeginDate() ?><?php endif ?>"
                            <?php if ($isViewing): ?>readonly<?php endif ?> />
                      
                        <label><?= i18n("Fecha de Fin") ?></label>
                        <input id="modalTaskEnd" name="endDate" type="date" required
                            value="<?php if (!is_null($task)): ?><?= $task->getEndDate() ?><?php endif ?>"
                            <?php if ($isViewing): ?>readonly<?php endif ?> />
                    </div>
                    <div class="form-row">
                        <label><?= i18n("Estado") ?></label>
                        <select id="modalTaskStatus" name="status" required
                            <?php if ($isViewing): ?>disabled<?php endif ?>>
                            <option value="ToDo"
                                <?php if (!is_null($task) && strcmp(trim($task->getStatus()), "ToDo") == 0): ?>selected<?php endif ?>><?= i18n("Por Hacer") ?></option>
                            <option value="Working"
                                <?php if (!is_null($task) && strcmp(trim($task->getStatus()), "Working") == 0): ?>selected<?php endif ?>><?= i18n("En Proceso") ?></option>
                            <option value="Done"
                                <?php if (!is_null($task) && strcmp(trim($task->getStatus()), "Done") == 0): ?>selected<?php endif ?>><?= i18n("Acabado") ?></option>
                        </select>
                    </div>
                    <div class="form-row">
                        <label><?= i18n("Prioridad") ?></label>
                        <select id="modalTaskPriority" name="priority" required
                            <?php if ($isViewing): ?>disabled<?php endif ?>>
                            <option value="Low"
                                <?php if (!is_null($task) && strcmp(trim($task->getPriority()), "Low") == 0): ?>selected<?php endif ?>><?= i18n("Baja") ?></option>
                            <option value="Medium"
                                <?php if (!is_null($task) && strcmp(trim($task->getPriority()), "Medium") == 0): ?>selected<?php endif ?>><?= i18n("Media") ?></option>
                            <option value="High"
                                <?php if (!is_null($task) && strcmp(trim($task->getPriority()), "High") == 0): ?>selected<?php endif ?>><?= i18n("Alta") ?></option>
                        </select>
                    </div>
                    <div class="form-row">
                        <label><?= i18n("Asignar a") ?></label>
                        <div id="modalTaskAssignees" class="Fcheckbox-list">

                            <?php foreach ($users as $user): ?>
                                <div>
                                    <input type="checkbox" name="<?= "user".$userNum ?>" value="<?= $user->getUserMail() ?>"
                                        <?php $userNum++ ?>
                                        <?php if ($isViewing): ?>disabled<?php endif ?>
                                        <?php if (in_array($user, $taskUsers)): ?> checked<?php endif ?> />
                                    <label>
                                        <?= $user->getUserMail() ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="Fmodal-footer">
                        <a href="index.php?controller=projects&amp;action=view&amp;id=<?= $projectID ?>">
                            <button class="Fbtn" id="cancelTaskBtn" type="button"><?= i18n("Cancelar") ?></button>
                        </a>
                        <button class="Fbtn primary" type="submit" id="saveTaskBtn"
                            <?php if ($isViewing): ?>
                            hidden="hidden"
                            <?php endif ?>><?= i18n("Guardar") ?></button>

                        <input type="hidden" id="editingTaskId" name="task_id" value="<?php if (!is_null($task)):?><?= $task->getId() ?><?php endif ?>" />
                        <input type="hidden" id="editingProjectId" name="id" value="<?php if (!is_null($task)): ?><?= $task->getProject() ?><?php endif ?><?php if (is_null($task)): ?><?= $projectID ?><?php endif ?>" />
                    </div>
                </div>
            </div>
            </div>
            
    </form>
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