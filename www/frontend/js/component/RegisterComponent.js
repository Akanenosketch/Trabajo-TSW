class RegisterComponent extends Fronty.ModelComponent {

    constructor(router) {

        let userModel = new UserModel();
        super(Handlebars.templates.userForm, userModel);

        //Config Models
        this.userModel = userModel;

        //Config Services
        this.userService = new UserService();

        //Config Router
        this.router = router;

        this.setupListeners();
    }

    setupListeners() {

        this.addEventListener('click', '#saveUserBtn', () => {
            this.saveUser();
        });

        this.addEventListener('click', '#cancelUserBtn', () => {
            this.router.goToPage('WelcomePage');
        });

    }


    onStart() {
        this.userModel.logout(); //limpia datos por si acaso
        this.userModel.setMode("register");
    }

    saveUser() {

        this.userModel.setMail($('#regCorreo').val());
        this.userModel.setName($('#regNombre').val());
        this.userModel.setPass($('#regPass').val());

        this.userService.register(this.userModel)
            .then(() => {

                this.userModel.setMode("");
                this.userModel.set((model) => {
                    model.errors = []
                });

                this.router.goToPage('WelcomePage');
            })
            .fail((xhr, errorThrown, statusText) => {
                if (xhr.status == 400) {
                    this.userModel.set((model) => {
                        model.errors = xhr.responseJSON;
                    });
                } else {
                    alert('an error has occurred during request: ' + statusText + '.' + xhr.responseText);
                }
            });

    }

}