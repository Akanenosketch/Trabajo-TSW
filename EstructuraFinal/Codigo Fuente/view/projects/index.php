<?php
//file: view/projects/index.php
require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();

$posts = $view->getVariable("projects");
$currentuser = $view->getVariable("currentusername");
?>

<!--Mostrar la lista de projectos-->
<!--Pastear el html-->

<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="../style/project_index.css">
    <title><?= i18n("Dashboard") ?></title>
    

</head>

<body>
    <header>
        <div>
            <div class="username" id="userDisplay"><?= i18n("Dashboard") ?>Usuario</div>
            <div class="small" id="userEmail"><?= i18n("Dashboard") ?>Lista de Proyectos</div>
        </div>
        <div>
            <button onclick="logout()" class="add-btn"><?= i18n("Dashboard") ?>Cerrar sesion</button>
        </div>
    </header>

    <section class="project-list">
        <div class="add-project-container">
            <button id="openNewProjectModal" class="add-btn"><?= i18n("Dashboard") ?>Añadir Proyecto</button>
        </div>

        <div class="content-center">
            <div class="table-container">
                <table id="projectsTable">
                    <thead>
                        <tr>
                            <th><?= i18n("Dashboard") ?>Nombre del Proyecto</th>
                            <th><?= i18n("Dashboard") ?>Tareas ToDo</th>
                            <th><?= i18n("Dashboard") ?>Tareas Working</th>
                            <th><?= i18n("Dashboard") ?>Tareas Done</th>
                        </tr>
                    </thead>
                    <tbody id="projectsContainer"></tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Modal para nuevo proyecto -->
    <div id="newProjectModal" class="modal-overlay" aria-hidden="true">
        <div class="modal-box">
            <h3><?= i18n("Dashboard") ?>Nuevo Proyecto</h3>
            <form id="addProjectForm">
                <div class="form-row">
                    <label><?= i18n("Dashboard") ?>Nombre del Proyecto</label>
                    <input id="newProjectName" type="text" required>
                </div>
                <div class="form-row">
                    <label><?= i18n("Dashboard") ?>Participantes</label>
                    <div id="initialUsers" class="checkbox-list"></div>
                </div>
                <!--ESTE STYLE AL CSS-->
                <div style="text-align:right;margin-top:12px">
                    <button type="button" class="btn" id="cancelProjectBtn"><?= i18n("Dashboard") ?>Cancelar</button>
                    <button type="submit" class="btn primary"><?= i18n("Dashboard") ?>Crear proyecto</button>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="./js/dashboard.js"></script>
</body>

</html>

















-----------------------------------------------------------------
















<!-- Ejemplo

<h1><?=i18n("Posts")?></h1>

<table border="1">
	<tr>
		<th><?= i18n("Title")?></th><th><?= i18n("Author")?></th><th><?= i18n("Actions")?></th>
	</tr>

	<?php foreach ($posts as $post): ?>
		<tr>
			<td>
				<a href="index.php?controller=posts&amp;action=view&amp;id=<?= $post->getId() ?>"><?= htmlentities($post->getTitle()) ?></a>
			</td>
			<td>
				<?= $post->getAuthor()->getUsername() ?>
			</td>
			<td>
				<?php
				//show actions ONLY for the author of the post (if logged)


				if (isset($currentuser) && $currentuser == $post->getAuthor()->getUsername()): ?>

				<?php
				// 'Delete Button': show it as a link, but do POST in order to preserve
				// the good semantic of HTTP
				?>
				<form
				method="POST"
				action="index.php?controller=posts&amp;action=delete"
				id="delete_post_<?= $post->getId(); ?>"
				style="display: inline"
				>

				<input type="hidden" name="id" value="<?= $post->getId() ?>">

				<a href="#" 
				onclick="
				if (confirm('<?= i18n("are you sure?")?>')) {
					document.getElementById('delete_post_<?= $post->getId() ?>').submit()
				}"
				><?= i18n("Delete") ?></a>

			</form>

			&nbsp;

			<?php
			// 'Edit Button'
			?>
			<a href="index.php?controller=posts&amp;action=edit&amp;id=<?= $post->getId() ?>"><?= i18n("Edit") ?></a>

		<?php endif; ?>

	</td>
</tr>
<?php endforeach; ?>

</table>
<?php if (isset($currentuser)): ?>
	<a href="index.php?controller=posts&amp;action=add"><?= i18n("Create post") ?></a>
<?php endif; ?>
-->