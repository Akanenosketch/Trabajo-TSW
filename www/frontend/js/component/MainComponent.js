class MainComponent extends Fronty.RouterComponent {

    constructor() {

        super('frontyapp', Handlebars.templates.main, 'maincontent'); //div donde se carga, plantilla, el id de donde va a meter los datos o algo asi 

        //Config Models
        this.userModel = new UserModel(); //Modelo para gestionar User
        this.usersModel = new UsersModel(); //Modelo para gestionar lista de Users
        this.categoriesModel = new CategoriesModel(); //Modelo para gestionar lista de Users
        this.projectsModel = new ProjectsModel(); //Modelo para gestionar Projects

        this.configRouter();

        Handlebars.registerHelper('currentPage', () => {
            return super.getCurrentPage();
        });

    }

    configRouter() {
        super.setRouterConfig({
            'CategoryAdd': {
                component: new CategoryAddComponent(this),
                title: I18n.translate("Añadir Categoria")
            },
            'CategoryEdit': {
                component: new CategoryEditComponent(this),
                title: I18n.translate("Editar Categoria")
            },
            'CategoryIndex': {
                component: new CategoryIndexComponent(this.categoriesModel, this),
                title: I18n.translate("Listar Categorias")
            },
            'CategoryView': {
                component: new CategoryViewComponent(this),
                title: I18n.translate("Datos de Categoria")
            },
            'Login': {
                component: new LoginComponent(this.userModel, this),
                title: I18n.translate("Iniciar Sesion")
            },
            'ProjectAdd': {
                component: new ProjectAddComponent(this.projectsModel, this.usersModel,this.categoriesModel, this),
                title: I18n.translate("Añadir Proyecto")
            },
            'ProjectEdit': {
                component: new ProjectEditComponent(this.projectsModel, this.usersModel,this.categoriesModel, this),
                title: I18n.translate("Editar Proyecto")
            },
            'ProjectIndex': {
                component: new ProjectIndexComponent(this.projectsModel, this.userModel, this),
                title: I18n.translate("Dashboard")
            },
            'ProjectView': {
                component: new ProjectViewComponent(this.projectsModel, this),
                title: I18n.translate("Info Proyecto")
            },
            'Register': {
                component: new RegisterComponent(this),
                title: I18n.translate("Registrarse")
            },
            'TaskAdd': {
                component: new TaskAddComponent(this.projectsModel, this),
                title: I18n.translate("Añadir Tarea")
            },
            'TaskEdit': {
                component: new TaskEditComponent(this.projectsModel, this),
                title: I18n.translate("Editar Tarea")
            },
            'TaskView': {
                component: new TaskViewComponent(this.projectsModel, this),
                title: I18n.translate("Ver Tarea")
            },
            'UserEdit': {
                component: new UserEditComponent(this.userModel, this),
                title: I18n.translate("Editar Usuario")
            },
            'WelcomePage': {
                component: new WelcomeComponent(this),
                title: I18n.translate("Pagina de Bienvenida")
            },
            defaultRoute: 'WelcomePage' //lo que se visualiza por defecto
        });

    }

}