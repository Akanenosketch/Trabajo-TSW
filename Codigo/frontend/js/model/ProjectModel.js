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
            this.todo = tasks.filter((task) => task.status == "ToDo");
            this.todoCount = todo.length;
            this.working = tasks.filter((task) => task.status == "Working");
            this.workingCount = working.length;
            this.done = tasks.filter((task) => task.status == "Done");
            this.doneCount = done.length;
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

    getTaskByID(taskID) {
        return this.tasks.find(task => task.id == taskID);
    }

    setName(name) {
        this.set((self) => {
            self.name = name;
        });
    }

    setUsers(users) {
        this.set((self) => {
            self.users = users;
            self.emails = users.map((user) => user.user_mail);

        });
    }

    setTasks(tasks) {
        this.set((self) => {
            self.tasks = tasks;
            self.todo = tasks.filter((task) => task.status == "ToDo");
            self.todoCount = todo.length;
            self.working = tasks.filter((task) => task.status == "Working");
            self.workingCount = working.length;
            self.done = tasks.filter((task) => task.status == "Done");
            self.doneCount = done.length;
        });
    }

}