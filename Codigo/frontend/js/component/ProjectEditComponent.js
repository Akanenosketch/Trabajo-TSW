/*    // los errores, el projecto a editar, los users DE TODA LA APP, el email
    actual, los users ACTUALES para edit, un contador de usuarios (lo de usernum
    hay que mantenerlo como esta en rest, usernum1 = email)
    //modelos = project, users,user


    TODO*/
class ProjectViewComponent extends Fronty.ModelComponent {

    constructor(projectsModel, usersModel, userModel, router) {

        super(Handlebars.templates.projectForm, projectsModel);

        //Config Models
        this.projectsModel = projectsModel;

        this.userModel = userModel;
        this.addModel('user', userModel);

        this.usersModel = usersModel;
        this.addModel('users', usersModel);

        //Config Router
        this.router = router;

        //Config Services
        this.projectService = new ProjectService();

        setupListeners();
    }

    setupListeners() {

        this.addEventListener('click', '#saveProjectBtn', () => {
            saveProject();
        });


        this.addEventListener('click', '#cancelProjectBtn', () => {
            this.router.goToPage('ProjectView?id=' + this.projectsModel.selectedProject.id);
        });

    }

    onStart() {
        var selectedId = this.router.getRouteQueryParam('id');
        this.loadProject(selectedId);
    }

    loadProject(projectID) {
        if (projectID != null) {
            this.projectService.findPost(postId)
                .then((project) => {
                    this.projectsModel.setSelectedProject(
                        new ProjectModel(false, project.id, project.name, project.users, project.tasks)
                    );
                });
        }
    }

    saveProject() {
        this.projectsModel.selectedProject.name = $('#newProjectName').val();
        //Almacenar usuarios
        let newUsers = {};
        let count = this.usersModel.usercount;
        for (let index = 0; index < count; index++) {
            if ($('#user'+index).length > 0) {
                newUsers['##user'+index] =$('#user'+index).val();
                // Proceed with value
            }
        }
        this.projectsModel.selectedProject.users = newUsers;

        this.projectService.createProject(this.projectsModel.selectedProject)
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

    }

}
