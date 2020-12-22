angular.module('myApp')
.component('landing',{
    templateUrl: 'vistas/landing.html',
    controller: function($location,$rootScope){
        ctrl = this;

        ctrl.$onInit = function(){
            
            $rootScope.info = {"editando": false, "id_tutorial": 0};
            
        }

        ctrl.irUrl = function($path){
            $location.url('/' + $path);
        }

    },
})