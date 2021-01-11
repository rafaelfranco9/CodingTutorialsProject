angular.module('myApp')
.component('showTutorial',{
    templateUrl: 'vistas/showTutorial.html',
    controller: function($http,$sce,$window,$location,$rootScope){
        ctrl = this;

        ctrl.$onInit = function(){
            ctrl.dataUsuario = {};
            ctrl.cargarTutorial($rootScope.tutorialSeleccionado);
            aumentarVisitas($rootScope.tutorialSeleccionado);
            window.scrollTo(0,0);
        }

        ctrl.cargarTutorial = function(id_tutorial){

            $http.get('api/TutorialUser/' + id_tutorial)
            .then(function(response){
                ctrl.dataUsuario = response.data[0];
            })
            .catch(function(response){
               
            });

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

                    //Darle el formato correcto al html guardado
                    if(ctrl.herramientasBaseDatos != undefined){

                        ctrl.herramientas = ctrl.herramientasBaseDatos.map(function(value){
                            value.html_tutorial = value.html_tutorial.replace(/@/g,'"');
                            value.html_view = value.html_view.replace(/@/g,'"');

                            if(value.valor != undefined){
                                value.valor = value.valor.replace(/@/g,'"');
                                value.valor = value.valor.replace(/<CS>/g,"'");
                                value.valor = value.valor.replace(/<br>/g, '\n');
                            }

                            return value;
                        })

                    }
                
                })
                .catch(function(response){
                    alert('No se pudo cargar correctamente el tutorial');
                    ctrl.irUrl('home');
                });


        }

        function aumentarVisitas(id_tutorial){

            $http.patch('api/Visitas',id_tutorial)
            .then(function(response){                    
            })
            .catch(function(response){
                alert('ocurrio un error!');
            });

        }

        ctrl.getString = function(obj){
            return $sce.trustAsHtml(obj.html_view);
        }

        ctrl.irUrl = function($path){
            $location.url('/' + $path);
        }
        ctrl.openLink = function(link){
            $window.open(link, '_blank');
        }


    }

});