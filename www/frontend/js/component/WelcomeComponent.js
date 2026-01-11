class WelcomeComponent extends Fronty.ModelComponent {

    constructor(router) {

        let defaultModel = new UserModel();
        super(Handlebars.templates.welcomePage, defaultModel);


        //Config Router
        this.router = router;

        this.setupListeners();

        // Load gradient
        document.body.classList.add('gradient-layout');

    }

    setupListeners() {

        this.addEventListener('click', '#openLogin', () => {
            this.router.goToPage('Login');
        });

        this.addEventListener('click', '#openRegister', () => {
            this.router.goToPage('Register');
        });


        this.addEventListener('click', '#es-button', () => {
            I18n.changeLanguage('default');
            document.location.reload();
        });

        this.addEventListener('click', '#en-button', () => {
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

        this.addEventListener('click', '#themeToggle', () => {
            let current = document.body.classList.contains('light-theme') ? 'light' : 'dark';
            let next = current === 'light' ? 'dark' : 'light';
            applyTheme(next);
            localStorage.setItem('theme', next);
        });


    }

}