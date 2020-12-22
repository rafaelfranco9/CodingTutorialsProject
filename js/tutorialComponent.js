app = angular.module("myApp");
app.component("tutorial",{
    templateUrl: 'vistas/tutorial.html',
    controller: function($sce,$window,$http,$location,$rootScope){
        ctrl = this;

        ctrl.$onInit = function(){
            
            ctrl.id_tutorial = undefined;
            ctrl.titulo = '';
            ctrl.descripcion = '';
            ctrl.imagen = 'Imagenes/default_tutorial.png';
            ctrl.etiquetas = [];
            ctrl.herramientas = [];
            ctrl.imagenActiva = undefined;
            ctrl.archivosImagenes = {};
            ctrl.ancho = 200;
            ctrl.alto = 200;
            ctrl.editando = false;
            ctrl.buscarInfoUsuario();

            if($rootScope.info != undefined){
                if($rootScope.info.editando == true){
                    ctrl.cargarTutorial($rootScope.info.id_tutorial);
                    ctrl.editando = true;
                }
            }
            
        }

        ctrl.agregar = function(herramienta){
            switch (herramienta) {
                case 'codigo':
                    creadorHerramienta('codigo');
                    break;
                case 'texto':
                    creadorHerramienta('texto');
                    break;
                case 'url':
                    creadorHerramienta('url');
                    break;
                case 'image':
                    creadorHerramienta('image');
                    break;
            
                default:
                    break;
            }

        }

        function creadorHerramienta(herramienta){

            obj = {
                indice:undefined,
                label:undefined,
                html_tutorial:undefined,
                html_view:undefined,
                valor:undefined
            }
            ctrl.herramientas.push(obj);
            cantidadObjetos = ctrl.herramientas.length-1;

            switch (herramienta) {
                case 'codigo':
                    ctrl.herramientas[cantidadObjetos].indice = cantidadObjetos;
                    ctrl.herramientas[cantidadObjetos].html_tutorial = '<textarea id="codigo" spellcheck="false" oninput="autosize(this)" ng-model="$ctrl.herramientas[' + cantidadObjetos + '].valor"  class="rounded m-1 codeinput" ></textarea>';
                    ctrl.herramientas[cantidadObjetos].html_view = '<textarea id="codigo" readonly spellcheck="false"  ng-model="$ctrl.herramientas[' + cantidadObjetos + '].valor"  class="rounded m-1 codeinput" ></textarea>';
                    ctrl.herramientas[cantidadObjetos].label = 'code';
                    break;
                case 'texto':
                    ctrl.herramientas[cantidadObjetos].indice = cantidadObjetos;
                    ctrl.herramientas[cantidadObjetos].html_tutorial = '<textarea id="texto" spellcheck="false" oninput="autosize(this)"  ng-model="$ctrl.herramientas[' + cantidadObjetos + '].valor" class="rounded m-1 textinput"></textarea>';
                    ctrl.herramientas[cantidadObjetos].html_view = '<textarea id="texto" readonly spellcheck="false"  ng-model="$ctrl.herramientas[' + cantidadObjetos + '].valor" class="rounded m-1 textinput"></textarea>';
                    ctrl.herramientas[cantidadObjetos].label = 'text';
                    break;
                case 'url':
                    ctrl.herramientas[cantidadObjetos].indice = cantidadObjetos;
                    ctrl.herramientas[cantidadObjetos].html_tutorial = '<input type="text" id="texto" spellcheck="false"  ng-model="$ctrl.herramientas[' + cantidadObjetos + '].valor" class="rounded m-1 urlinput"><button class="btn btn-sm mb-1 bg-light" ng-click="$ctrl.openLink($ctrl.herramientas[' + cantidadObjetos + '].valor)">Ir</button>';
                    ctrl.herramientas[cantidadObjetos].html_view = '<input type="text" id="texto" readonly spellcheck="false" ng-model="$ctrl.herramientas[' + cantidadObjetos + '].valor" class="rounded m-1 urlinput"><button class="btn btn-sm mb-1 bg-light" ng-click="$ctrl.openLink($ctrl.herramientas[' + cantidadObjetos + '].valor)">Ir</button>';
                    ctrl.herramientas[cantidadObjetos].label = 'url';
                    break;
                case 'image':
                    ctrl.herramientas[cantidadObjetos].indice = cantidadObjetos;
                    ctrl.herramientas[cantidadObjetos].urlImagen = 'Imagenes/default_img.png';
                    ctrl.herramientas[cantidadObjetos].html_tutorial = '<img id="image_display_' + cantidadObjetos + '" class="img-thumbnail" ng-src="{{$ctrl.editando ? $ctrl.herramientas[' + cantidadObjetos + '].valor : $ctrl.herramientas[' + cantidadObjetos + '].urlImagen}}" style="width:{{$ctrl.herramientas[' + cantidadObjetos + '].ancho}}px; height:{{$ctrl.herramientas[' + cantidadObjetos + '].alto}}px" alt="user_image"><button ng-click="$ctrl.imgActiva(' + cantidadObjetos + ')" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-sm btn-dark">Add/Change Image</button>';
                    ctrl.herramientas[cantidadObjetos].html_view = '<img id="image_display_' + cantidadObjetos + '" class="img-thumbnail" class="img-thumbnail" ng-src="{{$ctrl.herramientas[' + cantidadObjetos + '].url}}" style="width: 200px; height: 200px;" alt="user_image">';
                    ctrl.herramientas[cantidadObjetos].label = 'image';
                    ctrl.herramientas[cantidadObjetos].ancho = ctrl.ancho;
                    ctrl.herramientas[cantidadObjetos].alto = ctrl.alto;
                    ctrl.imagenActiva = cantidadObjetos;
                    break;
            
                default:                            
                    break;
            }


        }
        
        ctrl.cargarTutorial = function(id_tutorial){

            $http.get('api/Tutorial/' + id_tutorial)
            .then(function(response){
                ctrl.tutorialEditar = response.data;
            
                ctrl.id_tutorial = ctrl.tutorialEditar.id;
                ctrl.titulo = ctrl.tutorialEditar.titulo;
                ctrl.descripcion = ctrl.tutorialEditar.descripcion;
                ctrl.imagen = ctrl.tutorialEditar.imagen;
                ctrl.etiquetas = JSON.parse(ctrl.tutorialEditar.etiquetas);                
                ctrl.herramientasBaseDatos = JSON.parse(ctrl.tutorialEditar.herramientas);

                ctrl.herramientas = ctrl.herramientasBaseDatos.map(function(value){
                    value.html_tutorial = value.html_tutorial.replace(/@/g,'"');
                    value.html_view = value.html_view.replace(/@/g,'"');
                    return value;
                })

                console.log(ctrl.herramientas);
               
            })
            .catch(function(response){
                alert('No se pudo cargar correctamente el tutorial');
                ctrl.irUrl('profile',2);
            });


        }

        ctrl.getString = function(obj){
            return $sce.trustAsHtml(obj.html_tutorial);
        }

        ctrl.openLink = function(link){
            $window.open('http://' + link, '_blank');
        }

        ctrl.guardar = function(opcion){
            
            //Guardar todas las imagenes
            ctrl.uploadFiles();

            //Convertir los comillas en @ para poder trabajar con json
            ctrl.herramientasBaseDatos = ctrl.herramientas.map(function(value){
                value.html_tutorial = value.html_tutorial.replace(/"/g,'@');
                value.html_view = value.html_view.replace(/"/g,'@');
                return value;
            })

            //Crear objeto con toda la informacion a enviar a la BD
            ctrl.tutorial = {
                'titulo': ctrl.titulo,
                'descripcion': ctrl.descripcion,
                'imagenTutorial':ctrl.imagen,
                'etiquetas': ctrl.etiquetas,
                'herramientas':ctrl.herramientasBaseDatos,
                'estado': opcion == 1? 'guardado' : 'publicado',
                'id_tutorial': ctrl.id_tutorial
            };

            //Guardar la informacion del tutorial
            $http.post('api/loadTutorial',ctrl.tutorial)
            .then(function(response){

                alert('se guardo el tutorial con exito');
                ctrl.irUrl('profile',2);
            })
            .catch(function(response){
                alert('no se guardo el tutorial con exito');
            });
        
        }

        ctrl.cargarFoto = function(){
            
            var file = document.getElementById('imgToLoad');
            ctrl.herramientas[ctrl.imagenActiva].urlImagen = URL.createObjectURL(file.files[0]);
            ctrl.herramientas[ctrl.imagenActiva].valor = 'usuarios/USER_' + ctrl.dataUsuario.id + '/imagenes/' + file.files[0].name;
            ctrl.herramientas[ctrl.imagenActiva].ancho = ctrl.ancho;
            ctrl.herramientas[ctrl.imagenActiva].alto = ctrl.alto;

            var key = ctrl.imagenActiva.toString();
            ctrl.archivosImagenes[key] = file.files[0];            

        }


        ctrl.imgActiva = function(num){
            ctrl.imagenActiva = num;
        }


        ctrl.uploadFiles = function(){

            var form_data = new FormData();
            var file = document.getElementById('imageFileTutorial');
            
            if(file.files[0]!= undefined){
                form_data.append('file[]',file.files[0]);
                ctrl.imagen = 'usuarios/USER_' + ctrl.dataUsuario.id + '/imagenes/' + file.files[0].name;
            }

            if(Object.keys(ctrl.archivosImagenes).length > 0){
               
                angular.forEach(ctrl.archivosImagenes,function(f){
                    form_data.append('file[]',f);
                });
            }
            
            $http.post('api/loadImages',form_data,
            {
                transformRequest:angular.identity,
                headers: {'Content-Type':undefined,'Process-Data':false}
            }).then(function(response){
                alert(response);
            });
           
        }

        ctrl.logout = function () {
			if(confirm("Desea salir del sistema?")){
				$auth.logout();
				$auth.removeToken();
				$location.url('/landing',2);
			}
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


        ctrl.irUrl = function($path,$opcion){
            
            if($opcion==1){
                if(confirm("Desea salir del editor?")){
                    $location.url('/' + $path);
                }
            }else{
                    $location.url('/' + $path);
            }
        }

    },

});

app.directive('compile',function($compile, $timeout){
    return{
        restrict:'A',
        link: function(scope,elem,attrs){
            $timeout(function(){                
                $compile(elem.contents())(scope);    
            });
        }        
    };
});





