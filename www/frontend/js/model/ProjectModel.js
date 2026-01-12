/**
 * objeto que contiene los parametros y ya para serializar y mostrar, equivale al array de datos que se recibe del back
 */
class ProjectModel extends Fronty.Model {


    /**
     * Crea un modelo de un projecto con los datos recibidos
     */
    constructor(toCreate, id, name, users, tasks, categories) {
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
            this.usersCount = users.length;
        }

        if (tasks) {
            this.tasks = tasks;
            this.tasksCount = tasks.length;
            this.todo = tasks.filter((task) => task.status == "ToDo");
            this.todoCount = this.todo.length;
            this.working = tasks.filter((task) => task.status == "Working");
            this.workingCount = this.working.length;
            this.done = tasks.filter((task) => task.status == "Done");
            this.doneCount = this.done.length;
            if (this.tasksCount == 0) {
                this.completedPercent = 0;
            } else {
                this.completedPercent = (this.doneCount * 100 / this.tasksCount);
                this.completedPercent = Math.round(this.completedPercent * 100) / 100; //Fija a 2 decimales
            }

        }

        if (categories) {
            this.categories = categories;
            this.catsCount = categories.length;
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

        if (!this.id) {
            this.id = "";
        }

        if (!this.name) {
            this.name = "";
        }

        if (!this.users) {
            this.users = "";
        }

        if (!this.tasks) {
            this.tasks = "";
        }

        if (!this.categories) {
            this.categories = "";
        }

        return {
            id: this.id,
            name: this.name,
            users: this.users,
            tasks: this.tasks,
            categories: this.categories
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
            self.usersCount = users.length;
        });
    }

    setCats(cats) {
        this.set((self) => {
            this.categories = cats;
            this.catsCount = cats.length;
        });
    }

    setTasks(tasks) {
        this.set((self) => {
            self.tasks = tasks;
            self.tasksCount = tasks.length;
            self.todo = tasks.filter((task) => task.status == "ToDo");
            self.todoCount = self.todo.length;
            self.working = tasks.filter((task) => task.status == "Working");
            self.workingCount = self.working.length;
            self.done = tasks.filter((task) => task.status == "Done");
            self.doneCount = self.done.length;
            if (this.tasksCount == 0) {
                this.completedPercent = 0;
            } else {
                this.completedPercent = (this.doneCount * 100 / this.tasksCount);
                this.completedPercent = Math.round(this.completedPercent * 100) / 100; //Fija a 2 decimales
            }

        });
    }

}