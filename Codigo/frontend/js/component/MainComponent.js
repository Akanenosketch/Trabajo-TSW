class MainComponent extends Fronty.RouterComponent {

    constructor() {

        super('frontyapp', Handlebars.templates.main, 'maincontent'); //div donde se carga, plantilla, el id de donde va a meter los datos o algo asi 

        //Config Models
        this.userModel = new UserModel(); //Modelo para gestionar User
        this.usersModel = new UsersModel(); //Modelo para gestionar lista de Users
        this.projectsModel = new ProjectsModel(); //Modelo para gestionar Projects

        configRouter();

        Handlebars.registerHelper('currentPage', () => {
            return super.getCurrentPage();
        });

        this.addChildComponent(this.createOptionsComponent());

    }

    configRouter() {
        super.setRouterConfig({
            'Login': {
                component: new LoginComponent(this.userModel, this),
                title: 'Login'
            },
            'ProjectAdd': {
                component: new ProjectAddComponent(this.projectsModel, this.usersModel, this),
                title: 'ProjectAdd'
            },
            'ProjectEdit': {
                component: new ProjectEditComponent(this.projectsModel, this.usersModel, this),
                title: 'ProjectEdit'
            },
            'ProjectIndex': {
                //       component: new PostAddComponent(this.postsModel, this.userModel, this),
                title: 'ProjectIndex'
            },
            'ProjectView': {
                //       component: new PostAddComponent(this.postsModel, this.userModel, this),
                title: 'ProjectView'
            },
            'Register': {
                component: new RegisterComponent(this),
                title: 'Register'
            },
            'TaskAdd': {
                //       component: new PostAddComponent(this.postsModel, this.userModel, this),
                title: 'TaskAdd'
            },
            'TaskEdit': {
                //       component: new PostAddComponent(this.postsModel, this.userModel, this),
                title: 'TaskEdit'
            },
            'TaskView': {
                //       component: new PostAddComponent(this.postsModel, this.userModel, this),
                title: 'TaskView'
            },
            'UserEdit': {
                component: new UserEditComponent(this.userModel, this),
                title: 'UserEdit'
            },
            'WelcomePage': {
                //                component: new PostAddComponent(this.postsModel, this.userModel, this),
                title: 'WelcomePage'
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