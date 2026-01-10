class CategoryService {

    constructor() {
        this.baseUrl = AppConfig.backendServer + 'categories/'
    }

    /**
     * Creates the category given
     * @param category the category to create 
     * @returns 
     */
    createCategory(category) {
        return $.ajax({
            url: this.baseUrl,
            method: 'POST',
            data: JSON.stringify(category),
            contentType: 'application/json'
        });
    }

    /**
     * Gets the category with the given name
     * @param catname the name of category to retrieve 
     * @returns 
     */
    getCategory(catname) {
        return $.get(this.baseUrl + catname);
    }

     /**
     * Updates the category with given name
     * @param catName the name of the category 
     * @param category the category to update
     */
    updateCategory(catName,category){
        return $.ajax({
            url: this.baseUrl + catName,
            method: 'PUT',
            data: JSON.stringify(category),
            contentType: 'application/json'
        });
    }

    /**
     * Deletes the category with given name
     * @param catName the name of the category to delete 
     * @returns 
     */
    deleteCategory(catName) {
        return $.ajax({
            url: this.baseUrl + catName,
            method: 'DELETE'
        });
    }

    /**
    * Lists the category names of all existing cats. 
    */
    listAllCats(){
        return $.get(this.baseUrl);
    }

}