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

        //ProjectView pagina de edit ProjectIndex

        super.setRouterConfig({
            /*
            posts: {
                component: new PostsComponent(this.postsModel, this.userModel, this),
                title: 'Posts'
            },
            'view-post': { //view post es el nombre para hacer go to, title ni idea, index.html#view-post
                component: new PostViewComponent(this.postsModel, this.userModel, this),
                title: 'Post'
            },
            'edit-post': {
                component: new PostEditComponent(this.postsModel, this.userModel, this),
                title: 'Edit Post'
            },
            'add-post': {
                component: new PostAddComponent(this.postsModel, this.userModel, this),
                title: 'Add Post'
            },
            login: {
                component: new LoginComponent(this.userModel, this),
                title: 'Login'
            },
            defaultRoute: 'posts' //lo que se visualiza por defecto, ponerla la welcome
            */
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