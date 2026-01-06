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