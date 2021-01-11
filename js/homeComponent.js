angular.module('myApp')
.component('home',{
    templateUrl:'vistas/home.html',
    controller: function($auth,$location,$http,$rootScope){
        ctrl = this;
        
		ctrl.datosUsuario = $auth.getPayload();
		ctrl.$onInit = function(){
            ctrl.inputSearch = '';
            ctrl.nuevaCategoria = '';
            ctrl.admin = false;
            ctrl.buscarTutoriales();
            cargarCategorias();
            isAdmin();
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

        ctrl.borrarCategoria = function(id){
            $http.delete('api/Categorias/' + id)
            .then(function(response){
                alert('se borro la categoria satisfactoriamente!');
                location.reload();
            })
            .catch(function(response){
                alert('error eliminando categoria');
            });
        }

        ctrl.agregarCategoria = function(){
            console.log(ctrl.nuevaCategoria);
            $http.post('api/Categorias',{"categoria":ctrl.nuevaCategoria})
            .then(function(response){
                alert('se agrego satisfactoriamente la categoria!');
                ctrl.nuevaCategoria = '';
                location.reload();
            })
            .catch(function(response){
                alert('error eliminando categoria');
            });
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

        function isAdmin(){

            $http.get('api/IsAdmin/' + ctrl.datosUsuario.id)
            .then(function(response){
                ctrl.admin = response.data;
            })
            .catch(function(response){
                
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