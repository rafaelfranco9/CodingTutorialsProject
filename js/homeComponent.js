angular.module('myApp')
.component('home',{
    templateUrl:'vistas/home.html',
    controller: function($auth,$location,$http){
        ctrl = this;
        
		ctrl.datosUsuario = $auth.getPayload();
		ctrl.$onInit = function(){
			ctrl.buscarTutoriales();
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


        ctrl.buscarTutoriales = function(){

            $http.get('api/PublishedTutorials')
            .then(function(response){
                ctrl.tutoriales = response.data;
                
            })
            .catch(function(response){
                alert('no funciona');
            });

        }

		ctrl.convertLabels = function(labels){
			return JSON.parse(labels);
		}


    }
})