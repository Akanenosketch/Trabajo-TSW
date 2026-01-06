/**
 * objeto que contiene los parametros y ya para serializar y mostrar, equivale al array de datos que se recibe del back
 */
class ProjectModel extends Fronty.Model {


    /**
     * Crea un modelo de un projecto con los datos recibidos
     */
    constructor(toCreate, id, name, users, tasks) {
        super('ProjectModel');

        if (id) {
            this.id = id;
        }

        if (name) {
            this.name = name;
        }

        if (users) {
            this.users = users;
            this.emails = users.map((user) => user.user_mail);
        }

        if (tasks) {
            this.tasks = tasks;
        }

        if (toCreate) {
            this.editing = !toCreate;
        } else {
            this.editing = true;
        }

    }

    /**
     * Usado para JSON.stringify, puede que el super() genere otros atributos que no queremos
     * @returns El objeto como string
     */
    toJSON() {
        return {
            id: this.id,
            name: this.name,
            users: this.users,
            tasks: this.tasks
        };
    }

    setName(name) {
        this.set((self) => {
            self.name = name;
        });
    }

    setUsers(users) {
        this.set((self) => {
            self.users = users;
        });
    }

    setTasks(tasks) {
        this.set((self) => {
            self.tasks = tasks;
        });
    }

}