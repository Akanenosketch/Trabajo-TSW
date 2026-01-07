class TaskEditComponent extends Fronty.ModelComponent {

    constructor(projectsModel, router) {
        let taskModel = new TaskModel("edit");
        super(Handlebars.templates.taskDorm, taskModel);

        //Config Models
        this.taskModel = taskModel;

        this.projectsModel = projectsModel;
        this.addModel('projects', projectsModel);

        //Config Services
        this.projectService = new ProjectService();
        this.taskService = new TaskService();

        //Config Router
        this.router = router;

        setupListeners();

    }

    setupListeners() {
        this.addEventListener('click', '#saveTaskBtn', () => {
            saveTask();
        });

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

    saveTask() {

        /*
                let provUserModel = new UserModel();
        provUserModel.setMail(this.userModel.user_mail);
        provUserModel.setName($('#regNombre').val());
        provUserModel.setPass($('#regPass').val());

        this.userService.edit(provUserModel)
            .then(() => {

                this.userModel.setLoggeduser(provUserModel);
                this.userModel.setMode("");
                this.userModel.set((model) => {
                      model.errors = []
                });

                this.router.goToPage('ProjectIndex');
            })
            .fail((xhr, errorThrown, statusText) => {
                if (xhr.status == 400) {
                    this.userModel.set((model) => {
                        model.errors = xhr.responseJSON;
                    });
                } else {
                    alert('an error has occurred during request: ' + statusText + '.' + xhr.responseText);
                }
            });


        */
        /*
        this.projectsModel.selectedProject.name = $('#newProjectName').val();
 //Almacenar usuarios
 let newUsers = {};
 let count = this.usersModel.usercount;
 for (let index = 0; index < count; index++) {
     if ($('#user' + index).is(':checked')) {
         newUsers['##user' + index] = $('#user' + index).val();
         // Proceed with value
     }
 }
 this.projectsModel.selectedProject.users = newUsers;
 
 this.projectService.updateProject(this.projectsModel.selectedProject.id, this.projectsModel.selectedProject)
     .then(() => {
         this.projectsModel.set((model) => {
             model.errors = []
         });
         this.router.goToPage('ProjectView?id=' + this.projectsModel.selectedProject.id);
     })
     .fail((xhr, errorThrown, statusText) => {
         if (xhr.status == 400) {
             this.projectsModel.set((model) => {
                 model.errors = xhr.responseJSON;
             });
         } else {
             alert('an error has occurred during request: ' + statusText + '.' + xhr.responseText);
         }
     });
 
 
 */
    }

}