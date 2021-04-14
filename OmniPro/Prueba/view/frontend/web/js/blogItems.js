define([
    'uiComponent',
    'ko',
    'jquery',
    'mage/storage',
    'mage/url'
], function (Component, ko, $, storage) {
    return Component.extend({
        defaults: {
            titulo: '',
            contenido: '',
            email: '',
            image: '',
            blogs: [],
            blogsUrl: '/rest/V1/blogs?searchCriteria',
            blogPostUrl: 'rest/V1/blogs'
        },
        initialize: function () {
            this._super();
            this.getBlogs();
            this.titulo.subscribe(function(value) {
                console.log(value)
            });
            var blogs1 = "/rest/V1/blogs?searchCriteria";
            $.ajax({
                url: blogs1
            }).done(function (response) {
                console.log(response);
            });
            return this;
        },
        initObservable: function () {
            this._super()
                .observe([
                    'titulo',
                    'contenido',
                    'email',
                    'image'
                ])
                .observe({
                    blogs: []
                });

            return this;
        },
        sendBlog: function () {
            var blog = {
                'blog': {
                    "title": this.titulo(),
                    "email": this.email(),
                    "content": this.contenido(),
                    "img": this.image()
                }
            };
            storage.post(this.blogPostUrl, JSON.stringify(blog))
                .then($.proxy(function () {
                    this.getBlogs();
                }, this));
        },
        getBlogs: function () {
            storage.get(this.blogsUrl)
                .then($.proxy(function (data) {
                    this.blogs(data.items);
                }, this));
        }
    });
});