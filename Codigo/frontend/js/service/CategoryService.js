class CategoryService {

    constructor() {
        this.baseUrl = AppConfig.backendServer + 'categories/'
    }

    //TODO AMPLIAR

    /**
    * Lists the emails of all existing users. 
    */
    listAllCats(){
        return $.get(this.baseUrl);
    }

}