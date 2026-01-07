class TaskViewComponent extends Fronty.ModelComponent {

    constructor(projectsModel, router) {
        let taskModel = new TaskModel("view");
        super(Handlebars.templates.taskForm, taskModel);

        //Config Models
        this.taskModel = taskModel;

        this.projectsModel = projectsModel;
        this.addModel('projects', projectsModel);

        //Config Services
        this.projectService = new ProjectService();

        //Config Router
        this.router = router;

        setupListeners();

    }

    setupListeners() {

        this.addEventListener('click', '#cancelTaskBtn', () => {
            this.router.goToPage('ProjectView?id=' + this.projectsModel.selectedProject.id);
        });

    }

    onStart() {
        let projectId = this.router.getRouteQueryParam('projectId');
        let taskId = this.router.getRouteQueryParam('taskId');
        this.loadProject(projectId);
        this.loadTask(taskId);
    }

    loadProject(projectID) {
        if (projectID != null) {
            this.projectService.getProject(projectID)
                .then((project) => {
                    this.projectsModel.setSelectedProject(
                        new ProjectModel(false, project.id, project.name, project.users, project.tasks)
                    );
                });
        }
    }

    loadTask(taskID) {
        if (taskID != null) {
            let task = this.projectsModel.selectedProject.getTaskByID(taskID);
            this.taskModel.setTask(task);
        }
    }

}