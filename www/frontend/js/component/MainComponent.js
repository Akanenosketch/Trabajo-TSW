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

        this.addChildComponent(this.createOptionsComponent());

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
                component: new LoginComponent(this.categoriesModel, this),
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

    createOptionsComponent() {
        let options = new Fronty.ModelComponent(Handlebars.templates.options, this.routerModel, 'langThemeOptions');

        // Language change
        options.addEventListener('click', '#es-button', () => {
            I18n.changeLanguage('default');
            document.location.reload();
        });

        options.addEventListener('click', '#en-button', () => {
            I18n.changeLanguage('en');
            document.location.reload();
        });

        // Theme change
        function applyTheme(theme) {
            let toggleBtn = document.getElementById('themeToggle');
            if (theme === 'light') {
                document.body.classList.add('light-theme');
                if (toggleBtn) toggleBtn.textContent = '🌞';
                if (toggleBtn) toggleBtn.setAttribute('aria-label', 'Switch to dark mode');
            } else {
                document.body.classList.remove('light-theme');
                if (toggleBtn) toggleBtn.textContent = '🌙';
                if (toggleBtn) toggleBtn.setAttribute('aria-label', 'Switch to light mode');
            }
        }

        // Determine initial theme: saved preference -> user preference -> default dark
        let saved = localStorage.getItem('theme');
        if (!saved) {
            localStorage.setItem('theme', "dark");
        }

        options.addEventListener('click', '#themeToggle', () => {
            let current = document.body.classList.contains('light-theme') ? 'light' : 'dark';
            let next = current === 'light' ? 'dark' : 'light';
            applyTheme(next);
            localStorage.setItem('theme', next);
        });

        return options;
    }

}