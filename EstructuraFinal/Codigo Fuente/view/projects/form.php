<?php
// file: view/projects/form.php
$view = ViewManager::getInstance();
$errors = $view->getVariable("errors");
$project = $view->getVariable("project");
$users = $view->getVariable("users");
$currentuserMail = $view->getVariable("currentusermail");
$projectUsers = $view->getVariable("projectUsers",array());
$userNum = 1;
?>

<!doctype html>
<html>
    <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="./view/css/theme.css">
    <link rel="stylesheet" type="text/css" href="./view/css/projects/form.css">
    <title><?= i18n("Formulario de Projectos") ?></title>
</head>
<body>

<div id="newProjectModal" class="modal-overlay" >
        <div class="modal-box">
            <h3>
                <?php if (!is_null($project)): ?><?= i18n("Editar Proyecto") ?><?php endif ?>
                <?php if (is_null($project)): ?><?= i18n("Nuevo Proyecto") ?><?php endif ?>
            </h3>
            <form id="addProjectForm" method="post"
            action="index.php?controller=projects&amp;action=<?php if (!is_null($project)): ?>edit<?php endif ?><?php if (is_null($project)): ?>add<?php endif ?>">

                <div class="form-row">
                    <label><?= i18n("Nombre del Proyecto") ?></label>
                    <input id="newProjectName" type="text" name="name" required minlength="1" 
                     value="<?php if (!is_null($project)): ?><?= $project->getName() ?><?php endif ?>">
                </div>
                <div class="form-row">
                    <label><?= i18n("Participantes") ?></label>
                    <div id="initialUsers" class="checkbox-list">



                    <?php foreach ($users as $user): ?>
                        <div>
                        <input type="checkbox" name="<?= "user".$userNum ?>" value="<?= $user->getUserMail() ?>"
                        <?php $userNum++ ?>
                        <?php if (in_array($user, $projectUsers) ): ?> checked<?php endif ?>     
                        
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
                          <?php if (!is_null($project)): ?>
                                 <input value="<?= $project->getId()?>" type="hidden" name="id">        
                         <?php endif ?>

                    <button type="submit" class="btn primary">
                        
                          <?php if (!is_null($project)): ?><?= i18n("Editar Proyecto") ?><?php endif ?>
                <?php if (is_null($project)): ?><?= i18n("Crear proyecto") ?><?php endif ?>
                        </button>
                </div>
            </form>
        </div>
    </div>
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
