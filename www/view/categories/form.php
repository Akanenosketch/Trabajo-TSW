<?php
// file: view/tasks/form.php
$view = ViewManager::getInstance();
$errors = $view->getVariable("errors");
$isViewing = $view->getVariable("isViewing", false);
$category = $view->getVariable("category");
?>


<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="./view/css/theme.css">
    <link rel="stylesheet" type="text/css" href="./view/css/unifiedThemes.css">
    <title><?= i18n("Formulario de Categorias") ?></title>
</head>

<body>

    <form action="index.php?controller=categories&amp;action=<?php if (!is_null($category) && !$isViewing): ?>edit<?php endif ?><?php if (is_null($category)): ?>add<?php endif ?>" method="post">
        <div id="categoryModal" class="modal-overlay">
            <div class="modal-box">
                <div class="modal-content">
                    <h3 id="categoryModalTitle">
                        <?php if (!is_null($category) && !$isViewing): ?><?= i18n("Editar Categoria") ?><?php endif ?><?php if (is_null($category)): ?><?= i18n("Añadir Categoria") ?><?php endif ?><?php if ($isViewing): ?><?= i18n("Datos de Categoria") ?><?php endif ?>
                    </h3>
                    <div class="form-row">
                        <label><?= i18n(key: "Nombre") ?></label>
                        <input id="modalCategoryName" name="name" type="text" required minlength="1"
                            value="<?php if (!is_null($category)): ?><?= $category->getName() ?><?php endif ?>"
                            <?php if ($isViewing || !is_null($category)):?>readonly="readonly"<?php endif ?>
                                 />
                    </div>
                    <div class="form-row">
                        <label><?= i18n("Descripcion") ?></label>
                        <input id="modalCategoryDesc" name="desc" type="text" required minlength="1"
                            value="<?php if (!is_null($category)): ?><?= $category->getDesc() ?><?php endif ?>"
                            <?php if ($isViewing): ?>readonly<?php endif ?> />
                    </div>

                    <div class="modal-footer">
                        <a href="index.php?controller=categories&amp;action=index">
                            <button class="btn" id="cancelCategoryBtn" type="button"><?= i18n("Cancelar") ?></button>
                        </a>
                        <button class="btn primary" type="submit" id="saveCategoryBtn"
                            <?php if ($isViewing): ?>
                            hidden="hidden"
                            <?php endif ?>><?= i18n("Guardar") ?></button>
                    </div>
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