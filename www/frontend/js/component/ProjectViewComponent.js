class ProjectViewComponent extends Fronty.ModelComponent {

    constructor(projectsModel, router) {

        super(Handlebars.templates.projectView, projectsModel);

        //Config Models
        this.projectsModel = projectsModel;

        //Config Services
        this.projectService = new ProjectService();
        this.taskService = new TaskService();

        //Config Router
        this.router = router;

        this.setupListeners();


    }


    setupListeners() {

        this.addEventListener('click', '#backBtn', () => {
            this.router.goToPage("ProjectIndex");
        });

        this.addEventListener('click', '#openTaskModal', () => {
            this.router.goToPage("TaskAdd?projectId=" + this.projectsModel.selectedProject.id);
        });

        this.addEventListener('click', '#editProjBtn', () => {
            this.router.goToPage("ProjectEdit?projectId=" + this.projectsModel.selectedProject.id);
        });

        this.addEventListener('click', '#deleteProjBtn', () => {
            this.projectService.deleteProject(this.projectsModel.selectedProject.id)
                .then(() => {
                    this.router.goToPage("ProjectIndex");
                })
                .fail(() => {
                    alert('Error Deleting')
                })
        });

    }

    onStart() {
        let selectedId = this.router.getRouteQueryParam('id');
        this.loadProject(selectedId);
    }

    loadProject(projectID) {

        if (projectID != null) {
            this.projectService.getProject(projectID)
                .then((project) => {
                    this.projectsModel.setSelectedProject(
                        new ProjectModel(false, project.id, project.name, project.users, project.tasks,project.categories)
                    );
                });
        }

    }

    updateProject() {
        this.loadProject(this.projectsModel.selectedProject.id);
    }

    // Override
    createChildModelComponent(className, element, id, modelItem) {
        return new TaskRowComponent(modelItem, this, this.taskService, this.router);
    }

}


class TaskRowComponent extends Fronty.ModelComponent {

    constructor(taskModel, projectViewComponent, taskService, router) {

        super(Handlebars.templates.taskRow, taskModel, null, null);

        this.projectViewComponent = projectViewComponent;

        //Config Models
        this.taskModel = taskModel;

        //Config Services
        this.taskService = taskService;

        //Config Router
        this.router = router;

        setupListeners();

    }

    setupListeners() {

        this.addEventListener('click', '#viewTaskBtn', () => {
            this.router.goToPage("TaskView?projectId=" + this.taskModel.projectID + "&taskId=" + this.taskModel.id);
        });

        this.addEventListener('click', '#editTaskBtn', () => {
            this.router.goToPage("TaskEdit?projectId=" + this.taskModel.projectID + "&taskId=" + this.taskModel.id);
        });

        this.addEventListener('click', '#deleteTaskBtn', () => {
            this.taskService.deleteTask(this.taskModel.projectID, this.taskModel.id)
                .fail(() => {
                    alert('Error Deleting')
                })
                .always(() => {
                    this.projectViewComponent.updateProject();
                });
        });

    }

}
