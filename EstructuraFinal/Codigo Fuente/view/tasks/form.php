<?php
// file: view/tasks/form.php
$view = ViewManager::getInstance();
$errors = $view->getVariable("errors");
$isViewing = $view->getVariable("isViewing", false);
$task = $view->getVariable("task");
$users = $view->getVariable("users");
$currentuserMail = $view->getVariable("currentusermail");
$projectID = $view->getVariable("projectID"); 
$userNum = 1;
?>


<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="./view/css/tasks/form.css">
    <title><?= i18n("Formulario de Tareas") ?></title>
</head>

<body>

    <form action="index.php?controller=tasks&amp;action=<?php if (!is_null($task) && !$isViewing): ?>edit<?php endif ?><?php if (is_null($task)): ?>add<?php endif ?>" method="post">
    <div id="taskModal" class="modal-overlay">

        <div class="modal-box">
            <div class="modal-content">
                <h3 id="taskModalTitle"><?= i18n("Añadir Tarea") ?></h3>
                <div class="form-row">
                    <label><?= i18n(key: "Nombre") ?></label>
                    <input id="modalTaskName" name="title" type="text" required minlength="1"
                    value="<?php if (!is_null($task)): ?><?= $task->getName() ?><?php endif ?>"
                    <?php if ($isViewing): ?>readonly<?php endif ?>
                    />
                </div>
                <div class="form-row">
                    <label><?= i18n("Descripcion") ?></label>
                    <textarea id="modalTaskDesc" name="desc" rows="3" minlength="1"
                     text="<?php if (!is_null($task)): ?><?= $task->getDesc()?><?php endif ?>"
                    <?php if ($isViewing): ?>readonly<?php endif ?>
                    ></textarea>
                </div>
                <div class="form-row">
                    <label><?= i18n("Estado") ?></label>
                    <select id="modalTaskStatus" name="status" required
                    <?php if ($isViewing): ?>disabled<?php endif ?>
                    >
                        <option value="ToDo" 
                        <?php if (!is_null($task) && strcmp(trim($task->status), "ToDo") == 0): ?>selected<?php endif ?>
                            ><?= i18n("ToDo") ?></option>
                        <option value="Working"
                        <?php if (!is_null($task) && strcmp(trim($task->status), "Working") == 0): ?>selected<?php endif ?>
                        ><?= i18n("Working") ?></option>
                        <option value="Done"
                        <?php if (!is_null($task) && strcmp(trim($task->status), "Done") == 0): ?>selected<?php endif ?>
                        ><?= i18n("Done") ?></option>
                    </select>
                </div>
                <div class="form-row">
                    <label><?= i18n("Asignar a") ?></label>
                    <div id="modalTaskAssignees" class="checkbox-list">
                    
                    <?php foreach ($users as $user): ?>
                      <div>
                        <input type="checkbox" name="<?= "user".$userNum ?>" value="<?= $user->getUserMail() ?>"
                        <?php $userNum++ ?>
                         <?php if ($isViewing): ?>disabled<?php endif ?>
                         <?php if (strcmp($currentuserMail, $user->getUserMail()) == 0): ?>checked required<?php endif ?>     
                        />
                        <label>
                            <?= $user->getUserMail() ?>
                        </label>
                    </div>

                    <?php endforeach; ?>

          
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                 <a href="index.php?controller=projects&amp;action=view&amp;id=<?= $projectID?>">
                <button class="btn" id="cancelTaskBtn" type="button"><?= i18n("Cancelar") ?></button>
                </a>
                <button class="btn primary" type="submit" id="saveTaskBtn"
                <?php if ($isViewing): ?>
                    hidden="hidden"
                <?php endif ?>
                
                ><?= i18n("Guardar") ?></button>

                <input type="hidden" id="editingTaskId" name="task_id" value="
                <?php if (!is_null($task)): ?>
                            <?= $task->getId() ?>
                <?php endif ?>
                "/>
                <input type="hidden" id="editingProjectId" name="id" value="<?php if (!is_null($task)):?><?= $task->getProject() ?><?php endif ?><?php if (is_null($task)): ?><?= $projectID ?><?php endif ?>"/>
            </div>
        </div>
    </div>
    </form>

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