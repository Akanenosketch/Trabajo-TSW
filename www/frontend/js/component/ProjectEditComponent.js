class ProjectEditComponent extends Fronty.ModelComponent {

    constructor(projectsModel, usersModel, categoriesModel, router) {

        super(Handlebars.templates.projectForm, projectsModel);

        //Config Models
        this.projectsModel = projectsModel;

        this.usersModel = usersModel;
        this.addModel('users', usersModel);

        this.categoriesModel = categoriesModel;
        this.addModel('categories', categoriesModel);

        //Config Services
        this.projectService = new ProjectService();
        this.userService = new UserService();
        this.categoryService = new CategoryService();

        //Config Router
        this.router = router;

        this.setupListeners();

    }

    setupListeners() {

        this.addEventListener('click', '#saveProjectBtn', () => {
            this.saveProject();
        });


        this.addEventListener('click', '#cancelProjectBtn', () => {
            this.router.goToPage('ProjectView?id=' + this.projectsModel.selectedProject.id);
        });

    }

    onStart() {
        var selectedId = this.router.getRouteQueryParam('id');
        this.loadProject(selectedId);
        this.userService.listAllUsers()
            .then((emails) => {
                this.usersModel.setUsers(emails);
            });
        this.categoryService.listAllCats()
            .then((cats) => {
                this.categoriesModel.setCats(cats.map(
                    (cat) => new CategoryModel("", cat.name)
                ));
            });
        this.projectsModel.set((model) => {
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

    saveProject() {
        this.projectsModel.selectedProject.name = $('#newProjectName').val();
        //Almacenar usuarios
        let newUsers = {};
        let count = this.usersModel.usercount;
        let userMails = this.usersModel.users;
        for (let index = 0; index < count; index++) {
            let key = '#' + $.escapeSelector(userMails[index]);
            if ($(key).is(':checked')) {
                newUsers[userMails[index]] = userMails[index];
            }
        }
        this.projectsModel.selectedProject.users = newUsers;

        //Almacenar categorias
        let newCats = {};
        let catNames = this.categoriesModel.categories.map(
            (cat) => cat.name
        );
        count = this.categoriesModel.catcount;
        for (let index = 0; index < count; index++) {
            let key = '#' + $.escapeSelector(catNames[index]);
            if ($(key).is(':checked')) {
                newCats[catNames[index]] = catNames[index];
            }
        }
        this.projectsModel.selectedProject.categories = newCats;

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

    }

}