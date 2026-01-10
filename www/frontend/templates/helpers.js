Handlebars.registerHelper('if_eq', function(a, b, opts) {
  if (a == b)
    return opts.fn(this);
  else
    return opts.inverse(this);
});

Handlebars.registerHelper('ifIncludes', function(item, array, options) {
  //Helper para comprobar desde HandleBars si un objeto esta incluido en un array
  if (array && array.indexOf(item) !== -1) {
    return options.fn(this); 
  }
  return options.inverse(this); 
});