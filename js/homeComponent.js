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
            ctrl.categoriaFiltrada = {};
            ctrl.reemplazoCategorias = {}
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
         

            $http.get('api/TutorialesConCategoria/' + id)
            .then(function(response){
               
                if(response.data > 0){
                    ctrl.categoriaFiltrada = ctrl.categorias.filter(ob => ob.id != id);
                    ctrl.reemplazoCategorias.idNuevo = ctrl.categoriaFiltrada[0];
                    $('#exampleModalCenter').modal('show');
                    ctrl.reemplazoCategorias.idViejo = id;

               }else{

                    if(confirm("Esta seguro que quiere borrar la categoria?")){
                        $http.delete('api/Categorias/' + id)
                        .then(function(response){
                            alert('se borro la categoria satisfactoriamente!');
                            location.reload();
                        })
                        .catch(function(response){
                            alert('error eliminando categoria');
                        });
                    }

               }
            })
            .catch(function(response){
                alert('error eliminando la categoria');            
            });

        }

        ctrl.agregarCategoria = function(){
            
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

        ctrl.actualizarCategoriaTutoriales = function(){
            ctrl.reemplazoCategorias.idNuevo = ctrl.reemplazoCategorias.idNuevo.id;

            $http.patch('api/ReemplazoCategoria',ctrl.reemplazoCategorias)
            .then(function(response){
                alert('Categorias reemplazadas, ahora si puede borrar la categoria que deseaba');
                location.reload();
            })
            .catch(function(response){
                alert('error reemplazando la categoria');
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