class ProjectsModel extends Fronty.Model {

  constructor() {
    super('ProjectsModel'); //call super le pone nombre al Model 

    // model attributes
    this.projects = [];
  }

  /**
   * Asigna el projecto a ver/editar/añadir
   * @param {*} project El ProjectModel sobre el que se opera
   */
  setSelectedProject(project) {
    this.set((self) => {
      self.selectedProject = project;
      if(self.selectedProject.tasks){
      self.selectedProject.setTasks(
        project.tasks.map(
          (task) => new TaskModel("", task.id, task.name, task.desc, task.projectID, task.status, task.users, task.priority, task.beginDate, task.endDate)
        ))

      }

    });
  }

  /**
   * Asigna la lista de projectos a mostrar
   * @param projects Una lista de ProjectModels 
   */
  setProjects(projects) {
    this.set((self) => {
      self.projects = projects;
    });
  }

}