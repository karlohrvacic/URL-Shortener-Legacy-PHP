const Url = window.location.href + "api/urls";

let values = {};
$.each($('#url').serializeArray(), function(i, field) {
    values[field.name] = field.value;
    console.log(field.name)

});
console.log(values)
