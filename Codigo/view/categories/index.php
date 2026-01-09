<?php
// file: view/categories/index.php
$view = ViewManager::getInstance();
$categories = $view->getVariable("categories");
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="./view/css/theme.css">
    <link rel="stylesheet" type="text/css" href="./view/css/categories/index.css">
    <title><?= i18n("Listado de Categorias") ?></title>
</head>

<body>
    <header>
        <div class="header-content">
            <div class="controls">
                <div class="lang-switch">
                    <a href="index.php?controller=language&amp;action=change&amp;lang=es" class="lang-btn">ES</a>
                    <a href="index.php?controller=language&amp;action=change&amp;lang=en" class="lang-btn">EN</a>
                </div>
                <button id="themeToggle" class="theme-toggle global"
                    aria-label="<?= i18n("Alternar tema") ?>">🌙</button>
                <a href="index.php?controller=projects&amp;action=index">
                    <button class="btn back"><?= i18n("Volver") ?></button>
                </a>
            </div>
        </div>
    </header>

    <main class="project-content">
        <div class="tasks-container">
            <div class="cols">
                <div class="col">
                    <h4><?= i18n("Listado de Categorias") ?></h4>
                    <div id="col-cat">
                        <?php foreach ($categories as $cat): ?>
                            <div class="cat">
                                <span><?= $cat->getName() ?>
                                </span>
                                <div>
                                    <a
                                        href="index.php?controller=categories&amp;action=view&amp;name=<?= $cat->getName() ?>">
                                        <button class="btn"><?= i18n("Ver") ?></button>
                                    </a>
                                    <a
                                        href="index.php?controller=categories&amp;action=edit&amp;name=<?= $cat->getName() ?>">
                                        <button class="btn"><?= i18n("Editar") ?></button>
                                    </a>
                                    <form method="post" action="index.php?controller=categories&amp;action=delete">
                                        <input value="<?= $cat->getName() ?>" type="hidden" name="name">
                                        <button class="btn danger" type="submit"><?= i18n("Borrar") ?></button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div class="task-actions">
            <a href="index.php?controller=categories&amp;action=add">
                <button class="btn primary action-btn" id="openCategoryModal"><?= i18n("+ Nueva Categoria") ?></button>
            </a>
        </div>
    </main>
    <script src="./view/js/theme.js"></script>
</body>

</html>