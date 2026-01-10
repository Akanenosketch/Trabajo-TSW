/* Main tswapp-front script */

//load external resources
function loadTextFile(url) {
  return new Promise((resolve, reject) => {
    $.get({
      url: url,
      cache: true,
      beforeSend: function (xhr) {
        xhr.overrideMimeType("text/plain");
      }
    }).then((source) => {
      resolve(source);
    }).fail(() => reject());
  });
}


// Configuration del backend
var AppConfig = {
  backendServer: 'http://localhost/rest/'
}

//Carga las plantillas, las compila, y las almacena
Handlebars.templates = {};
Promise.all([

  I18n.initializeCurrentLanguage('js/i18n'),

  loadTextFile('templates/components/category-form.hbs').then((source) =>
    Handlebars.templates.categoryForm = Handlebars.compile(source)),

  loadTextFile('templates/components/category-index.hbs').then((source) =>
    Handlebars.templates.categoryIndex = Handlebars.compile(source)),

  loadTextFile('templates/components/category-row.hbs').then((source) =>
    Handlebars.templates.categoryRow = Handlebars.compile(source)),

  loadTextFile('templates/components/main.hbs').then((source) =>
    Handlebars.templates.main = Handlebars.compile(source)),

  loadTextFile('templates/components/project-form.hbs').then((source) =>
    Handlebars.templates.projectForm = Handlebars.compile(source)),

  loadTextFile('templates/components/project-index.hbs').then((source) =>
    Handlebars.templates.projectIndex = Handlebars.compile(source)),

  loadTextFile('templates/components/project-row.hbs').then((source) =>
    Handlebars.templates.projectRow = Handlebars.compile(source)),

  loadTextFile('templates/components/project-view.hbs').then((source) =>
    Handlebars.templates.projectView = Handlebars.compile(source)),

  loadTextFile('templates/components/task-form.hbs').then((source) =>
    Handlebars.templates.taskForm = Handlebars.compile(source)),

  loadTextFile('templates/components/task-row.hbs').then((source) =>
    Handlebars.templates.taskRow = Handlebars.compile(source)),

  loadTextFile('templates/components/user-form.hbs').then((source) =>
    Handlebars.templates.userForm = Handlebars.compile(source)),

  loadTextFile('templates/components/welcome-page.hbs').then((source) =>
    Handlebars.templates.welcomePage = Handlebars.compile(source))

])
  .then(() => {
    $(() => {
      new MainComponent().start();
    });
  }).catch((err) => {
    alert('FATAL: could not start app ' + err);
  });
