class TaskService {

    constructor() {
        this.baseUrl = AppConfig.backendServer + 'projects/'
    }

    /**
     * Creates the task given in the given project
     * @param projectID the ID of the project 
     * @param task the task to create
     * @returns 
     */
    createTask(projectID, task) {
        return $.ajax({
            url: this.baseUrl + projectID + "/tasks",
            method: 'POST',
            data: JSON.stringify(task),
            contentType: 'application/json'
        });
    }

    /**
     * Updates the task with given ID in the given project
     * @param projectID the ID of the project 
     * @param taskID the ID of the task to update
     * @param task the task to update
     */
    updateTask(projectID, taskID, task) {
        return $.ajax({
            url: this.baseUrl + projectID + "/tasks/" + taskID,
            method: 'PUT',
            data: JSON.stringify(task),
            contentType: 'application/json'
        });
    }

    /**
     * Deletes the task with given ID in the given project
     * @param projectID the ID of the project 
     * @param taskID the ID of the task to delete
     */
    deleteTask(projectID, taskID) {
        return $.ajax({
            url: this.baseUrl + projectID + "/tasks/" + taskID,
            method: 'DELETE'
        });
    }

}