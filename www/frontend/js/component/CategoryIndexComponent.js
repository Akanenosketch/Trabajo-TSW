class CategoryIndexComponent extends Fronty.ModelComponent {

    constructor(categoriesModel, router) {

        super(Handlebars.templates.categoryIndex, categoriesModel);

        //Config Models
        this.categoriesModel = categoriesModel;

        //Config Services
        this.categoryService = new CategoryService();

        //Config Router
        this.router = router;

        this.setupListeners();

    }

    setupListeners() {

        this.addEventListener('click', '#backBtn', () => {
            this.router.goToPage("ProjectIndex");
        });

        this.addEventListener('click', '#openCategoryModal', () => {
            this.router.goToPage("CategoryAdd");
        });

    }


    onStart() {
        this.refresh();
    }

    refresh() {
        this.categoryService.listAllCats()
            .then((cats) => {
                this.categoriesModel.setCats(
                    cats.map(
                        (cat) => new CategoryModel("", cat.name)
                    ));
            });
    }

    // Override
    createChildModelComponent(className, element, id, modelItem) {
        return new CategoryRowComponent(modelItem, this, this.categoryService, this.router);
    }

}
class CategoryRowComponent extends Fronty.ModelComponent {

    constructor(categoryModel, categoryIndexComponent, categoryService, router) {

        super(Handlebars.templates.categoryRow, categoryModel);

        this.categoryIndexComponent = categoryIndexComponent;

        //Config Models
        this.categoryModel = categoryModel;

        //Config Services
        this.categoryService = categoryService;

        //Config Router
        this.router = router;

        this.setupListeners();

    }

    setupListeners() {

        this.addEventListener('click', '#viewCatBtn', () => {
            this.router.goToPage("CategoryView?catName=" + this.categoryModel.name);
        });

        this.addEventListener('click', '#editCatBtn', () => {
            this.router.goToPage("CategoryEdit?catName=" + this.categoryModel.name);
        });

        this.addEventListener('click', '#deleteCatBtn', () => {
            this.categoryService.deleteCategory(this.categoryModel.name)
                .fail(() => {
                    alert('Error Deleting')
                })
                .always(() => {
                    this.categoryIndexComponent.refresh();
                });
        });

    }

}
