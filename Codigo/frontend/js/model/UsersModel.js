class UsersModel extends Fronty.Model {

  constructor() {
    super('UsersModel'); //call super le pone nombre al Model 

    // model attributes
    this.users = [];
  }

  /**
   * Asigna la lista de users a mostrar
   * @param users Una lista de emails?
   */
  setUsers(users) {
    this.set((self) => {
      self.users = users;
    });
  }
  
}