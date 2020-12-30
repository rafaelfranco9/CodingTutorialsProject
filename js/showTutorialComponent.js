angular.module('myApp')
.component('showTutorial',{
    templateUrl: 'vistas/showTutorial.html',
    controller: function($http,$sce,$window,$location,$rootScope){
        ctrl = this;

        ctrl.$onInit = function(){
            ctrl.buscarInfoUsuario();
            ctrl.cargarTutorial($rootScope.tutorialSeleccionado);
        }

        ctrl.cargarTutorial = function(id_tutorial){

            $http.get('api/Tutorial/' + id_tutorial)
                
                .then(function(response){
                    //Respuesta del servidor
                    ctrl.tutorialEditar = response.data;

                    //Cargar los datos a las variables que corresponden
                    ctrl.id_tutorial = ctrl.tutorialEditar.id;
                    ctrl.titulo = ctrl.tutorialEditar.titulo;
                    ctrl.imagen = ctrl.tutorialEditar.imagen;
                    ctrl.descripcion = ctrl.tutorialEditar.descripcion != null ? ctrl.tutorialEditar.descripcion : undefined;
                    ctrl.etiquetas = ctrl.tutorialEditar.etiquetas != null ? JSON.parse(ctrl.tutorialEditar.etiquetas) : undefined;                
                    ctrl.herramientasBaseDatos = ctrl.tutorialEditar.herramientas != null ? JSON.parse(ctrl.tutorialEditar.herramientas) : undefined;
                    console.log( ctrl.descripcion);

                    //Darle el formato correcto al html guardado
                    if(ctrl.herramientasBaseDatos != undefined){

                        ctrl.herramientas = ctrl.herramientasBaseDatos.map(function(value){
                            value.html_tutorial = value.html_tutorial.replace(/@/g,'"');
                            value.html_view = value.html_view.replace(/@/g,'"');

                            if(value.valor != undefined){
                                value.valor = value.valor.replace(/@/g,'"');
                                value.valor = value.valor.replace(/<br>/g, '\n');
                            }

                            return value;
                        })

                    }
                    console.log('se ejecuto');
                
                })
                .catch(function(response){
                    alert('No se pudo cargar correctamente el tutorial');
                    ctrl.irUrl('home');
                });


        }

        ctrl.buscarInfoUsuario = function(){

            $http.get('api/ProfileInfo')
            .then(function(response){
                ctrl.dataUsuario = response.data;
            })
            .catch(function(response){
                alert('No se pudo cargar correctamente el tutorial');
                ctrl.irUrl('home');
            });

        }

        ctrl.getString = function(obj){
            return $sce.trustAsHtml(obj.html_view);
        }

        ctrl.irUrl = function($path){
            $location.url('/' + $path);
        }
        ctrl.openLink = function(link){
            $window.open('http://' + link, '_blank');
        }


    }

});