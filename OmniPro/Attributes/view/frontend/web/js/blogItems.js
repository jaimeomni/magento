define([
    'uiComponent',
    'ko',
    'jquery',
    'mage/storage'
], function(Component, ko, $, storage) {
    return Component.extend({
        defaults: {
            blogs: [],
            blogsUrl: '/rest/V1/blogs?searchCriteria',
            textoPrueba: "Texto Prueba",
            variable1: 25
        },
        initialize: function() {
            this._super();
            this.getBlogs();
            setTimeout($.proxy(function() {
                this.textoPrueba("Prueba 2");
                console.log(this);
            }, this), 1000)
            var blogs1 = "/rest/V1/blogs?searchCriteria";
            $.ajax({
                url: blogs1
            }).done(function (response) {
                console.log(response);
            });
            return this;
        },
        initObservable: function() {
            this._super()
                .observe([
                    'variable1',
                    'textoPrueba'
                ])
                .observe({
                    blogs: []
                });

            return this;
        },
        cambiarVariable: function() {
            this.variable1(0);
        },
        getBlogs: function() {
            storage.get(this.blogsUrl)
            .then($.proxy(function(data) {
                this.blogs(data.items);
            },this));
        }
    });
});