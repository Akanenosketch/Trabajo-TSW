class LoginComponent extends Fronty.ModelComponent {

    constructor(userModel, router) {

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
            this.login();
        });

        this.addEventListener('click', '#cancelUserBtn', () => {
            this.router.goToPage('WelcomePage');
        });

    }


    onStart() {
        this.userModel.setMode("login");
    }

    login() {
        let user_mail = $('#regCorreo').val();
        let passwd = $('#regPass').val();
        if (user_mail == "") {
            this.userModel.set((model) => {
                model.errors = {user_mail};
            });

        } else {
            this.userService.login(user_mail, passwd)
                .then((userData) => {

                    this.userModel.setLoggeduser(userData);
                    this.userModel.setMode("");
                    this.userModel.set((model) => {
                        model.errors = []
                    });

                    this.router.goToPage('ProjectIndex');
                })
                .catch((xhr, errorThrown, statusText) => {
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

}