class ProjectIndexComponent extends Fronty.ModelComponent {

    constructor(projectsModel, userModel, router) {

        super(Handlebars.templates.projectIndex, projectsModel);

        //Config Models
        this.projectsModel = projectsModel;

        this.userModel = userModel;
        this.addModel('user', userModel);

        //Config Services
        this.projectService = new ProjectService();
        this.userService = new UserService();

        //Config Router
        this.router = router;
        this.setupListeners();

    }

    setupListeners() {

        this.addEventListener('click', '#openNewProjectModal', () => {
            this.router.goToPage("ProjectAdd");
        });

        
        this.addEventListener('click', '#listCatBtn', () => {
            this.router.goToPage("CategoryIndex");
        });

        this.addEventListener('click', '#editUserBtn', () => {
            this.router.goToPage("UserEdit");
        });


        this.addEventListener('click', '#logoutBtn', () => {
            this.userModel.logout();
            this.userService.logout();
            this.router.goToPage("WelcomePage");
        });

    }

    onStart() {
        this.updateProjects();
    }


    updateProjects() {

        this.projectService.listProjects()
            .then((projectsData) => {

                this.projectsModel.setProjects(
                    projectsData.map(
                        (project) => new ProjectModel("", project.id, project.name, project.users, project.tasks,project.categories)
                    ));
            });

    }

    // Override
    createChildModelComponent(className, element, id, modelItem) {
        return new ProjectRowComponent(modelItem);
    }

}



class ProjectRowComponent extends Fronty.ModelComponent {

    constructor(projectModel) {

        super(Handlebars.templates.projectRow, projectModel, null, null);

    }

}