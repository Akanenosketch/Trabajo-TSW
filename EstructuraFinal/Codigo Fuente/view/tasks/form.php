<?php
// file: view/users/login.php
$view = ViewManager::getInstance();
$errors = $view->getVariable("errors");
$isViewing = $view->getVariable("isViewing", false);
$task = $view->getVariable("task");
$users = $view->getVariable("users");
$currentuserMail = $view->getVariable("currentusermail");
?>



<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" type="text/css" href=./view/css/tasks/form.css>
    <title><?= i18n("Formulario de Tareas") ?></title>
</head>

<body>

    <!-- Modales para añadir/editar tarea y administrar usuarios -->
    <div id="taskModal" class="modal-overlay" aria-hidden="true">
        <div class="modal-box">
            <div class="modal-content">
                <h3 id="taskModalTitle"><?= i18n("Añadir Tarea") ?></h3>
                <div class="form-row">
                    <label><?= i18n(key: "Nombre") ?></label>
                    <input id="modalTaskName" name="title" type="text" required>


                    a los inputs ponerles los valores en edit y el readonly en view
                    html poliglota cerrar input
                </div>
                <div class="form-row">
                    <label><?= i18n("Descripcion") ?></label>
                    <textarea id="modalTaskDesc" name="desc" rows="3"></textarea>
                </div>
                <div class="form-row">
                    <label><?= i18n("Estado") ?></label>
                    <select id="modalTaskStatus" name="status">
                        <option value="ToDo"><?= i18n("ToDo") ?></option>
                        <option value="Working"><?= i18n("Working") ?></option>
                        <option value="Done"><?= i18n("Done") ?></option>
                    </select>
                </div>
                <div class="form-row">
                    <label><?= i18n("Asignar a") ?></label>
                    <div id="modalTaskAssignees" class="checkbox-list">
                    
                    for each
                    <div><label><input type="checkbox" value="d" id="assg-q3n761" checked=""> d</label></div>

                    </div>
                </div>
            </div>
            Los botones que tengan acciones que toquen
            <div class="modal-footer">
                <button class="btn" id="cancelTaskBtn"><?= i18n("Cancelar") ?></button>
                <button class="btn primary" id="saveTaskBtn"><?= i18n("Guardar") ?></button>
                <input type="hidden" id="editingTaskId" name="task_id" value="">
            </div>
        </div>
    </div>


    Pillar del js como se crea el checkbox y revisar que los nombres de campos sean los esperados
COMO SE LE PASA EL ID DE PROYECTO

</body>

</html>