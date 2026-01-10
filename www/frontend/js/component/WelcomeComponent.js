class WelcomeComponent extends Fronty.ModelComponent {

    constructor(router) {

        let defaultModel= new UserModel();
        super(Handlebars.templates.welcomePage, defaultModel);

        //Config Router
        this.router = router;

        this.setupListeners();

    }

    setupListeners() {

        this.addEventListener('click', '#openLogin', () => {
            this.router.goToPage('Login');
        });

        this.addEventListener('click', '#openRegister', () => {
            this.router.goToPage('Register');
        });

    }

}