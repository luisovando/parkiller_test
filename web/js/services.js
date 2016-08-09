(function () {
    'use strict';
    angular.module('parkiller.services', ['ngResource', 'geoh.configs.module'])
        .factory('Register', function ($resource, Config) {
            var baseURL = Config.SERVER_URL;
            return $resource(baseURL + '/register/:id', {id: '@id'}, {
                update: {
                    method: 'PUT'
                },
                delete: {
                    method: 'DELETE'
                }
            }, {
                stripTrailingSlashes: true
            });
        })
        .factory('Travel', function ($resource, Config) {
            var baseURL = Config.SERVER_URL;
            return $resource(baseURL + '/travels/:id', {id: '@id'}, {
                update: {
                    method: 'PUT'
                },
                delete: {
                    method: 'DELETE'
                },
                getClient: {
                    method: 'POST',
                    url: baseURL + '/locations/client'
                }
            }, {
                stripTrailingSlashes: true
            });
        })
})();
