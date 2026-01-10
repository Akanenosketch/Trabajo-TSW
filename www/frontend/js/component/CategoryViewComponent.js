class CategoryViewComponent extends Fronty.ModelComponent {

    constructor(router) {
        let categoryModel = new CategoryModel("view");
        super(Handlebars.templates.categoryForm, categoryModel);

        //Config Models
        this.categoryModel = categoryModel;

        //Config Services
        this.categoryService = new CategoryService();

        //Config Router
        this.router = router;

        this.setupListeners();

    }

    setupListeners() {

        this.addEventListener('click', '#cancelCategoryBtn', () => {
            this.router.goToPage('CategoryIndex');
        });

    }

    onStart() {
        let catName = this.router.getRouteQueryParam('catName');
        this.loadCat(catName);
    }

    loadCat(catName) {
        if (catName != null) {
            this.categoryService.getCategory(catName)
                .then((category) => {
                    this.categoryModel.setCat(category);
                });
        }
    }

}