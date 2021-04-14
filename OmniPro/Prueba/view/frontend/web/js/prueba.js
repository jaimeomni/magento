/* requirejs([
    'jquery',
    'underscore'
], function ($, _){
    var blogs = "/rest/V1/blogs?searchCriteria";
    $.ajax({
        url: blogs
    }).done(function (response) {
        console.log(response);
    })
}) */

define([
    'jquery',
    'underscore'
], function ($, _) {
    return function (config) {
        console.log(config['prueba-parametro']);
        var blogs = "/rest/V1/blogs?searchCriteria";


        $.ajax({
            url: blogs
        }).done(function (response) {
            console.log(response);
        });
    }
})