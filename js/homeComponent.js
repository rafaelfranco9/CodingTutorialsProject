angular.module('myApp')
.component('home',{
    templateUrl:'vistas/home.html',
    controller: function($auth,$location,$http,$rootScope){
        ctrl = this;
        
		ctrl.datosUsuario = $auth.getPayload();
		ctrl.$onInit = function(){
            ctrl.buscarTutoriales();
            cargarCategorias();
            ctrl.inputSearch = '';
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

        function cargarCategorias(){
            $http.get('api/Categorias')
            .then(function(response){
                ctrl.categorias = response.data;
            })
            .catch(function(response){
                alert('error cargando categorias');
            });
        }


        ctrl.buscarTutoriales = function(){

            $http.get('api/PublishedTutorials')
            .then(function(response){
                ctrl.tutoriales = response.data;
            })
            .catch(function(response){
                alert('no funciona');
            });
            ctrl.clicked = undefined;
            ctrl.inputSearch = '';
        }

        ctrl.filtrarPorCategoria = function(cat){
            ctrl.clicked = cat;
            $http.get('api/PublishedTutorialsFilter/' + cat)
            .then(function(response){
                ctrl.tutoriales = response.data;
            })
            .catch(function(response){
                alert('no funciona');
            });

        }

        ctrl.buscar = function(){

            if(ctrl.inputSearch != ''){
                $http.get('api/PublishedTutorialsSearch/' + ctrl.inputSearch)
                .then(function(response){
                    ctrl.tutoriales = response.data;
                })
                .catch(function(response){
                    alert('no funciona');
                });
            }
        }

        ctrl.abrirTutorial = function(id){
            $rootScope.tutorialSeleccionado = id;
            ctrl.irUrl('showTutorial');
        }

		ctrl.convertLabels = function(labels){
			return JSON.parse(labels);
		}


    }
})