class UsersModel extends Fronty.Model {

  constructor() {
    super('UsersModel'); //call super le pone nombre al Model 

    // model attributes
    this.users = [];
    this.usercount = 0;
  }

  /**
   * Asigna la lista de users a mostrar
   * @param users Una lista de emails?
   */
  setUsers(users) {
    this.set((self) => {
      self.users = users;
      self.usercount = users.length;
    });
  }
  
}