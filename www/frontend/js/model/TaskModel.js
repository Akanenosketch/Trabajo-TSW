class TaskModel extends Fronty.Model {


    /**
     * Crea un modelo de un projecto con los datos recibidos
     */
    constructor(mode, id, name, desc, projectID, status, users, priority, beginDate, endDate) {
        super('TaskModel');

        if (id) {
            this.id = id;
        }

        if (name) {
            this.name = name;
        }

        if (desc) {
            this.desc = desc;
        }

        if (projectID) {
            this.projectID = projectID;
        }

        if (status) {
            this.status = status;
        }

        if (users) {
            this.users = users;
            this.emails = users.map((user) => user.user_mail);
        }

        if (priority) {
            this.priority = priority;
        }

        if (beginDate) {
            this.beginDate = beginDate;
        }

        if (endDate) {
            this.endDate = endDate;
        }

        if (mode) {
            this.mode = mode;
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

        if (!this.desc) {
            this.desc = "";
        }

        if (!this.projectID) {
            this.projectID = "";
        }

        if (!this.status) {
            this.status = "";
        }

        if (!this.users) {
            this.users = "";
        }
     
        if (!this.priority) {
            this.priority = "";
        }

        if (!this.beginDate) {
            this.beginDate = "";
        }

        if (!this.endDate) {
            this.endDate = "";
        }

        return {
            id: this.id,
            name: this.name,
            desc: this.desc,
            projectID: this.projectID,
            status: this.status,
            users: this.users,
            priority: this.priority,
            beginDate: this.beginDate,
            endDate: this.endDate
        };
    }

    setTask(task) {
        this.set((self) => {
            self.id = task.id;
            self.name = task.name;
            self.desc = task.desc;
            self.projectID = task.projectID;
            self.status = task.status;
            self.users = task.users;
            self.emails = task.users.map((user) => user.user_mail);
            self.priority = task.priority;
            self.beginDate = task.beginDate;
            self.endDate = task.endDate;
        });
    }

    setID(id) {
        this.set((self) => {
            self.id = id;
        });
    }

    setName(name) {
        this.set((self) => {
            self.name = name;
        });
    }

    setDesc(desc) {
        this.set((self) => {
            self.desc = desc;
        });
    }

    setProjectID(projectID) {
        this.set((self) => {
            self.projectID = projectID;
        });
    }

    setStatus(status) {
        this.set((self) => {
            self.status = status;
        });
    }

    setUsers(users) {
        this.set((self) => {
            self.users = users;
            self.emails = users.map((user) => user.user_mail);
        });
    }

    setPriority(priority) {
        this.set((self) => {
            self.priority = priority;
        });
    }

    setBeginDate(beginDate) {
        this.set((self) => {
            self.beginDate = beginDate;
        });
    }

    setEndDate(endDate) {
        this.set((self) => {
            self.endDate = endDate;
        });
    }

    setMode(mode) {
        this.set((self) => {
            self.mode = mode;
        });
    }

}