angular.module("myApp")
.component("login",{
    templateUrl:'vistas/login.html',
    controller: function($auth,$location,$http){
        ctrl = this;

        ctrl.$onInit = function(){
            ctrl.usuarioIncorrecto = false; 
            ctrl.nuevoUsuario = {}; 
        }

        ctrl.login = function(){
			$auth.login({"email": ctrl.email, "password": ctrl.password }) 
				.then(function(response) {
					$auth.setToken(response.data.jwt);
					$location.url('/home');
				})
				.catch(function(response) {
					limpiarFormularios();
                    ctrl.usuarioIncorrecto = true; 
				});
        };

        ctrl.irSignUp = function(){
            $location.url('/signUp');
        }

        function limpiarFormularios(){
            ctrl.email = '';
            ctrl.password = '';
            ctrl.nuevoUsuario = {}; 
        }

    },

})