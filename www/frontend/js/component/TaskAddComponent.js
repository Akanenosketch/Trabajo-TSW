class TaskAddComponent extends Fronty.ModelComponent {

    constructor(projectsModel, router) {
        let taskModel = new TaskModel("add");
        super(Handlebars.templates.taskForm, taskModel);

        //Config Models
        this.taskModel = taskModel;

        this.projectsModel = projectsModel;
        this.addModel('projects', projectsModel);

        //Config Services
        this.projectService = new ProjectService();
        this.taskService = new TaskService();

        //Config Router
        this.router = router;

        this.setupListeners();


    }

    setupListeners() {

        this.addEventListener('click', '#saveTaskBtn', () => {
            this.saveTask();
        });

        this.addEventListener('click', '#cancelTaskBtn', () => {
            this.router.goToPage('ProjectView?id=' + this.projectsModel.selectedProject.id);
        });

    }

    onStart() {
        let projectId = this.router.getRouteQueryParam('projectId');
        this.loadProject(projectId);
        this.taskModel.set((model) => {
            model.errors = []
        });
    }

    loadProject(projectID) {
        if (projectID != null) {
            this.projectService.getProject(projectID)
                .then((project) => {
                    this.projectsModel.setSelectedProject(
                        new ProjectModel(false, project.id, project.name, project.users, project.tasks, project.categories)
                    );
                });
        }
    }

    saveTask() {

        //Almacenar los datos de la task en el taskModel
        //No editar ID o ProjectId
        this.taskModel.setName($('#modalTaskName').val());
        this.taskModel.setDesc($('#modalTaskDesc').val());
        this.taskModel.setStatus($('#modalTaskStatus').val());

        let newUsers = {};
        let count = this.projectsModel.selectedProject.users.length;
        let userMails = this.projectsModel.selectedProject.emails;
        for (let index = 0; index < count; index++) {
            let key = '#' +$.escapeSelector(userMails[index]);
            if ($(key).is(':checked')) {
                newUsers[userMails[index]] = userMails[index];
            }
        }
        this.taskModel.setUsers(newUsers);
        this.taskModel.setPriority($('#modalTaskPriority').val());
        this.taskModel.setBeginDate($('#modalTaskBegin').val());
        this.taskModel.setEndDate($('#modalTaskEnd').val());

        this.taskService.create(this.projectsModel.selectedProject.id, this.taskModel)
            .then(() => {
                this.taskModel.set((model) => {
                    model.errors = []
                });
                this.router.goToPage('ProjectView?id=' + this.projectsModel.selectedProject.id);
            })
            .fail((xhr, errorThrown, statusText) => {
                if (xhr.status == 400) {
                    this.taskModel.set((model) => {
                        model.errors = xhr.responseJSON;
                    });
                } else {
                    alert('an error has occurred during request: ' + statusText + '.' + xhr.responseText);
                }
            });

    }

}
