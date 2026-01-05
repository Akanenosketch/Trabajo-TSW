/**
 * objeto que contiene los parametros y ya para serializar y mostrar, equivale al array de datos que se recibe del back
 */
class ProjectModel extends Fronty.Model {


    /**
     * Crea un modelo de un projecto con los datos recibidos
     */
    constructor(id, name, users, tasks) {
        super('ProjectModel');

        if (id) {
            this.id = id;
        }

        if (name) {
            this.name = name;
        }

        if (users) {
            this.users = users;
        }

        if (tasks) {
            this.tasks = tasks;
        }

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
