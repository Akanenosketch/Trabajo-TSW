class UserModel extends Fronty.Model {
    constructor(mode, username, user_mail, passwd) {
        super('UserModel');

        if (username) {
            this.username = username;
        }

        if (user_mail) {
            this.user_mail = user_mail;
        }

        if (passwd) {
            this.passwd = passwd;
        }

        this.isLogged = false;

        if (mode) {
            this.mode = mode;
        }

    }

    /**
    * Usado para JSON.stringify, puede que el super() genere otros atributos que no queremos
    * @returns El objeto como string
    */
    toJSON() {
        return {
            username: this.username,
            user_mail: this.user_mail,
            passwd: this.passwd
        };
    }

    setLoggeduser(loggedUser) {
        this.set((self) => {
            self.username = loggedUser.username;
            self.user_mail = loggedUser.user_mail;
            self.passwd = loggedUser.passwd;

            self.isLogged = true;
        });
    }

    logout() {
        this.set((self) => {
            delete self.username;
            delete self.user_mail;
            delete self.passwd;
            self.isLogged = false;
        });
    }

    setName(username) {
        this.set((self) => {
            self.username = username;
        });
    }

    setMail(user_mail) {
        this.set((self) => {
            self.user_mail = user_mail;
        });
    }

    setPass(passwd) {
        this.set((self) => {
            self.passwd = passwd;
        });
    }

    setMode(mode) {
        this.set((self) => {
            self.mode = mode;
        });
    }

}