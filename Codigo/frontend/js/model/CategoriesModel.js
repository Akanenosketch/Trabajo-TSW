class CategoriesModel extends Fronty.Model {

    constructor() {
        super('CategoriesModel'); //call super le pone nombre al Model 

        // model attributes
        this.categories = [];
        this.catcount = 0;
    }


    setCats(cats) {
        this.set((self) => {
            self.categories = cats;
            self.catcount = cats.length;
        });
    }

}