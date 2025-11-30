<?php
// file: view/projects/form.php
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
    <link rel="stylesheet" type="text/css" href="./view/css/projects/form.css">
    <title><?= i18n("Formulario de Projectos") ?></title>
</head>
    <div id="newProjectModal" class="modal-overlay" aria-hidden="true">
        <div class="modal-box">
            <h3>
                <?php if (!is_null($project)): ?><?= i18n("Editar Proyecto") ?><?php endif ?>
                <?php if (is_null($project)): ?><?= i18n("Nuevo Proyecto") ?><?php endif ?>
            </h3>
            <form id="addProjectForm" method="post"
            action="index.php?controller=projects&amp;action=<?php if (!is_null($project)): ?>edit<?php endif ?>
                <?php if (is_null($project)): ?>add<?php endif ?>">

                <div class="form-row">
                    <label><?= i18n("Nombre del Proyecto") ?></label>
                    <input id="newProjectName" type="text" name="name" required minlength="1">
                </div>
                <div class="form-row">
                    <label><?= i18n("Participantes") ?></label>
                    <div id="initialUsers" class="checkbox-list">



                    <?php foreach ($users as $user): ?>
                        <div>
                        <input type="checkbox" name="<?php $user->getUserMail() ?>" value="<?php $user->getUserMail() ?>"
                        <?php if (in_array($user, $projectUsers) ): ?> checked<?php endif ?>     
                        <?php if (strcmp($currentuserMail, $user->getUserMail()) == 0): ?> required disabled<?php endif ?>     
                        />
                        <label>
                            <?= $user->getUserMail() ?>
                        </label>
                    </div>

                    <?php endforeach; ?>


                    </div>

                </div>
                <div>
                <?php if (is_null($project)): ?>
                  <a href="index.php?controller=projects&amp;action=index">
                <?php endif ?>
                <?php if (!is_null($project)): ?>
                  <a href="index.php?controller=projects&amp;action=view&amp;id=<?= $project->getId()?>">                    
                <?php endif ?>
                       <button type="button" class="btn" id="cancelProjectBtn"><?= i18n("Cancelar") ?></button>
                    </a>

                    <button type="submit" class="btn primary">
                        
                          <?php if (!is_null($project)): ?><?= i18n("Editar Proyecto") ?><?php endif ?>
                <?php if (is_null($project)): ?><?= i18n("Crear proyecto") ?><?php endif ?>
                        </button>
                </div>
            </form>
        </div>
    </div>
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