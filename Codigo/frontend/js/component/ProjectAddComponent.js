class ProjectAddComponent extends Fronty.ModelComponent {

    constructor(projectsModel, usersModel, router) {

        super(Handlebars.templates.projectForm, projectsModel);

        //Config Models
        this.projectsModel = projectsModel;

        this.usersModel = usersModel;
        this.addModel('users', usersModel);

        //Config Services
        this.projectService = new ProjectService();
        this.userService = new UserService();

        //Config Router
        this.router = router;

        this.setupListeners();
    }

    setupListeners() {

        this.addEventListener('click', '#saveProjectBtn', () => {
            this.saveProject();
        });


        this.addEventListener('click', '#cancelProjectBtn', () => {
            this.router.goToPage('ProjectIndex');
        });

    }

    onStart() {
        this.projectsModel.setSelectedProject(new ProjectModel(true));
        this.userService.listAllUsers()
            .then((emails) => {
                this.usersModel.setUsers();
            });
    }

    saveProject() {
        this.projectsModel.selectedProject.name = $('#newProjectName').val();
        //Almacenar usuarios
        let newUsers = {};
        let count = this.usersModel.usercount;
        for (let index = 0; index < count; index++) {
            if ($('#user' + index).is(':checked')) {
                newUsers['user' + index] = $('#user' + index).val();
            }
        }
        this.projectsModel.selectedProject.users = newUsers;

        this.projectService.createProject(this.projectsModel.selectedProject)
            .then(() => {
                this.projectsModel.set((model) => {
                    model.errors = []
                });
                this.router.goToPage('ProjectIndex');
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