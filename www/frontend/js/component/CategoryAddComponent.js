class CategoryAddComponent extends Fronty.ModelComponent {

    constructor(router) {
        let categoryModel = new CategoryModel("add");
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

        this.addEventListener('click', '#saveCategoryBtn', () => {
            this.saveCat();
        });

        this.addEventListener('click', '#cancelCategoryBtn', () => {
            this.router.goToPage('CategoryIndex');
        });

    }

    onStart() {
        let catName = this.router.getRouteQueryParam('catName');
        this.loadCat(catName);
        this.categoryModel.set((model) => {
            model.errors = []
        });

    }

    loadCat(catName) {
        if (catName != null) {
            this.categoryService.getCategory(catName)
                .then((category) => {
                    this.categoryModel.setCat(category);
                });
        }
    }

    saveCat() {

        this.categoryModel.setName($('#modalCategoryName').val());
        this.categoryModel.setDesc($('#modalCategoryDesc').val());

        this.categoryService.createCategory(this.categoryModel)
            .then(() => {
                this.categoryModel.set((model) => {
                    model.errors = []
                });
                this.router.goToPage('CategoryIndex');
            })
            .fail((xhr, errorThrown, statusText) => {
                if (xhr.status == 400) {
                    this.categoryModel.set((model) => {
                        model.errors = xhr.responseJSON;
                    });
                } else {
                    alert('an error has occurred during request: ' + statusText + '.' + xhr.responseText);
                }
            });
    }

}