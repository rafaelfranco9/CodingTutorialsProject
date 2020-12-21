angular.module('myApp')
.component('landing',{
    templateUrl: 'vistas/landing.html',
    controller: function($location){
        ctrl = this;

        ctrl.irUrl = function($path){
            $location.url('/' + $path);
        }

    },
})