var angularMap = angular.module('Maps', [
    'ngMap', 'ngMaterial', 'ngMessages', 'ngResource', 'geoh.configs.module', 'parkiller.services', 'ngLodash'
])
    .run(function ($rootScope, NgMap) {
        NgMap.getMap().then(function (map) {
            $rootScope.map = map;
        });
    });