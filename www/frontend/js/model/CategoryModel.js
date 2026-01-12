class CategoryModel extends Fronty.Model {


    /**
     * Crea un modelo de un projecto con los datos recibidos
     */
    constructor(mode, name, desc) {
        super('CategoryModel');

        if (name) {
            this.name = name;
        }

        if (desc) {
            this.desc = desc;
        }

        if (mode) {
            this.mode = mode;
        }

    }

    /**
     * Usado para JSON.stringify, puede que el super() genere otros atributos que no queremos
     * @returns El objeto como string
     */
    toJSON() {

        if (!this.desc) {
            this.desc = "";
        }

        if (!this.name) {
            this.name = "";
        }

        return {
            name: this.name,
            desc: this.desc
        };
    }

    setCat(cat) {
        this.set((self) => {
            self.name = cat.name;
            self.desc = cat.desc;
        });
    }

    setName(name) {
        this.set((self) => {
            self.name = name;
        });
    }

    setDesc(desc) {
        this.set((self) => {
            self.desc = desc;
        });
    }

    setMode(mode) {
        this.set((self) => {
            self.mode = mode;
        });
    }

}