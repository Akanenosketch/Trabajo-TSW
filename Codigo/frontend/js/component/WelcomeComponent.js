class WelcomeComponent extends Fronty.ModelComponent {

    constructor(router) {

        super(Handlebars.templates.welcomePage, userModel);

        //Config Router
        this.router = router;

        this.setupListeners();

    }

    setupListeners() {

        this.addEventListener('click', '#openLogin', () => {
            this.router.goToPage('Register');
        });

        this.addEventListener('click', '#openRegister', () => {
            this.router.goToPage('Login');
        });

    }

}