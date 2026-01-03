class UserService {

    constructor() {

        this.baseUrl = AppConfig.backendServer + 'users/'
    }

    /**
     * Retrieves session variables to login automatically.
     * @returns The login field (user_mail) promise
     */
    loginWithSessionData() {
        var self = this;
        return new Promise((resolve, reject) => {
            if (window.sessionStorage.getItem('login') &&
                window.sessionStorage.getItem('pass')) {
                self.login(window.sessionStorage.getItem('login'), window.sessionStorage.getItem('pass'))
                    .then(() => {
                        resolve(window.sessionStorage.getItem('login'));
                    })
                    .catch(() => {
                        reject();
                    });
            } else {
                resolve(null);
            }
        });
    }

    /**
     * Stores the credentials to sent them with all requests and logins
     * @param {*} login The user_mail
     * @param {*} pass The password
     * @returns 
     */
    login(login, pass) {
        return new Promise((resolve, reject) => {

            $.get({
                url: this.baseUrl + login,
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("Authorization", "Basic " + btoa(login + ":" + pass));
                }
            })
                .then(() => {
                    //keep this authentication forever
                    window.sessionStorage.setItem('login', login);
                    window.sessionStorage.setItem('pass', pass);
                    $.ajaxSetup({
                        beforeSend: (xhr) => {
                            xhr.setRequestHeader("Authorization", "Basic " + btoa(login + ":" + pass));
                        }
                    });
                    resolve();
                })
                .fail((error) => {
                    window.sessionStorage.removeItem('login');
                    window.sessionStorage.removeItem('pass');
                    $.ajaxSetup({
                        beforeSend: (xhr) => { }
                    });
                    reject(error);
                });
        });
    }

    /**
     * Logs out
     */
    logout() {
        window.sessionStorage.removeItem('login');
        window.sessionStorage.removeItem('pass');
        $.ajaxSetup({
            beforeSend: (xhr) => { }
        });
    }

    /**
     * Registers the given user
     * @param  user EL usuario a registrar, con username, user_mail y password
     * @returns the ajax request
     */
    register(user) {
        return $.ajax({
            url: this.baseUrl,
            method: 'POST',
            data: JSON.stringify(user),
            contentType: 'application/json'
        });
    }


    /**
     * Edits the current user
     * @param  user the editted user 
     */
    edit(user) {
        var login = window.sessionStorage.getItem('login');
        var pass = user.password;
        return new Promise((resolve, reject) => {
            $.ajax({
                url: this.baseUrl+ login,
                method: 'PUT',
                data: JSON.stringify(user),
                contentType: 'application/json'
            })
                .then(() => {
                    //keep this authentication forever
                    window.sessionStorage.setItem('pass', pass);
                    $.ajaxSetup({
                        beforeSend: (xhr) => {
                            xhr.setRequestHeader("Authorization", "Basic " + btoa(login + ":" + pass));
                        }
                    });
                    resolve();
                })
                .fail((error) => {
                    reject(error);
                });
        });

    }
}
