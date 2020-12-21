angular.module("myApp")
.component("profile",{
    templateUrl:'vistas/profile.html',
    controller: function($http,$location){
        ctrl = this;

        ctrl.$onInit = function(){
            ctrl.tutoriales = [];
            ctrl.buscarInfoUsuario();
            ctrl.buscarTutoriales();
        }


        ctrl.buscarTutoriales = function(){

            $http.get('api/ProfileTutorials')
            .then(function(response){
                ctrl.tutoriales = response.data;
                
            })
            .catch(function(response){
                alert('no funciona');
            });

        }

        ctrl.buscarInfoUsuario = function(){

            $http.get('api/ProfileInfo')
            .then(function(response){
                ctrl.dataUsuario = response.data;
                
            })
            .catch(function(response){
                alert('no funciona');
            });

        }

        ctrl.logout = function () {
			if(confirm("Desea salir del sistema?")){
				$auth.logout();
				$auth.removeToken();
				$location.url('/landing');
			}
		}

    },
});