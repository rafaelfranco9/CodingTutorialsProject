angular.module('myApp')
.component('landing',{
    templateUrl: 'vistas/landing.html',
    controller: function($location){
        ctrl = this;

        ctrl.iniciarSesion = function(){
            $location.url('/login');
        }

        ctrl.registrarse = function(){
            $location.url('/signUp');     
        }

    },
})