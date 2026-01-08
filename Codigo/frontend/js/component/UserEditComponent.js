class UserEditComponent extends Fronty.ModelComponent {

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
            saveUser();
        });

        this.addEventListener('click', '#cancelUserBtn', () => {
            this.router.goToPage('ProjectIndex');
        });

    }


    onStart() {
        this.userModel.setMode("edit");
    }

    saveUser() {
        let provUserModel = new UserModel();
        provUserModel.setMail(this.userModel.user_mail);
        provUserModel.setName($('#regNombre').val());
        provUserModel.setPass($('#regPass').val());

        this.userService.edit(provUserModel)
            .then(() => {

                this.userModel.setLoggeduser(provUserModel);
                this.userModel.setMode("");
                this.userModel.set((model) => {
                    model.errors = []
                });

                this.router.goToPage('ProjectIndex');
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