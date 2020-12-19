angular.module("myApp")
.component("signUp",{
    templateUrl: 'vistas/signUp.html',
    controller: function($auth,$location,$http){
        ctrl = this;

        ctrl.$onInit = function(){
            ctrl.nuevoUsuario = {}; 
            ctrl.errorNuevoUsuario = false;
        }

        ctrl.signUp = function(){

            $http.post('api/SignUp',ctrl.nuevoUsuario)
            .then(function(response){
                alert("Usuario creado con exito, ingrese ahora!");
                limpiarFormularios();
                ctrl.errorNuevoUsuario = false;
                $location.url('/login');
            })
            .catch(function(response){
                ctrl.errorNuevoUsuario = true;
            });

        }

        ctrl.irLogin = function(){
            $location.url('/login');
        }

        function limpiarFormularios(){
            ctrl.email = '';
            ctrl.password = '';
            ctrl.nuevoUsuario = {}; 
        }


    },

})