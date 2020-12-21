angular.module("myApp")
.component("profile",{
    templateUrl:'vistas/profile.html',
    controller: function($http,$location,$rootScope){
        ctrl = this;

        ctrl.$onInit = function(){
            ctrl.tutoriales = [];
            ctrl.buscarInfoUsuario();
            ctrl.buscarTutoriales();
            $rootScope.info = {"editando": false, "id_tutorial": 0};
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

        ctrl.editarTutorial = function(id){
            $rootScope.info = {"editando": true, "id_tutorial": id};
            ctrl.irUrl('tutorial');
        }
        ctrl.borrarTutorial = function(id){
            if(confirm("Esta seguro que quiere borrar el tutorial?")){
                $http.delete('api/Tutorial/' + id)
                .then(function(response){
                    alert('Se borro satisfactoriamente!');
                    location.reload();
                })
                .catch(function(response){
                    alert('NO se borro satisfactoriamente!');
                });

            }
        }

        ctrl.logout = function () {
			if(confirm("Desea salir del sistema?")){
				$auth.logout();
				$auth.removeToken();
				$location.url('/landing');
			}
        }
        
        ctrl.irUrl = function($path){
            $location.url('/' + $path);
        }

    },
});