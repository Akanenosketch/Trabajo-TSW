class ProjectService {

    constructor() {
        this.baseUrl = AppConfig.backendServer + 'projects/'
    }

    /**
     * Creates the project given
     * @param project the project to create 
     * @returns 
     */
    createProject(project) {
        return $.ajax({
            url: this.baseUrl,
            method: 'POST',
            data: JSON.stringify(project),
            contentType: 'application/json'
        });
    }

    /**
     * Gets the project with the given ID
     * @param projectID the ID of project to retrieve 
     * @returns 
     */
    getProject(projectID) {
        return $.get(this.baseUrl + projectID);
    }

     /**
     * Updates the project with given ID
     * @param projectID the ID of the project 
     * @param project the project to update
     */
    updateProject(projectID,project){
        return $.ajax({
            url: this.baseUrl + projectID,
            method: 'PUT',
            data: JSON.stringify(project),
            contentType: 'application/json'
        });
    }

    /**
     * Deletes the project with given ID
     * @param projectID the ID of the project to delete 
     * @returns 
     */
    deleteProject(projectID) {
        return $.ajax({
            url: this.baseUrl + projectID,
            method: 'DELETE'
        });
    }

    /**
     * Lists all the projects of the current user
     * @returns 
     */
    listProjects() {
        return $.get(this.baseUrl.substring(0, this.baseUrl.length - 1));
    }

}