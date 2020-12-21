app = angular.module("myApp");
app.component("tutorial",{
    templateUrl: 'vistas/tutorial.html',
    controller: function($sce,$window,$http){
        ctrl = this;

        ctrl.$onInit = function(){
            
            ctrl.id_tutorial = undefined;
            ctrl.titulo = '';
            ctrl.descripcion = '';
            ctrl.imagen = 'Imagenes/default_img.png';
            ctrl.etiquetas = [];
            ctrl.herramientas = [];
            ctrl.imagenActiva = undefined;
            ctrl.archivosImagenes = {};
            ctrl.ancho = 200;
            ctrl.alto = 200;
            
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
                    ctrl.herramientas[cantidadObjetos].html_tutorial = '<img id="image_display_' + cantidadObjetos + '" class="img-thumbnail" ng-src="Imagenes/default_img.png" style="width: 200px; height: 200px;" alt="user_image"><button ng-click="$ctrl.imgActiva(' + cantidadObjetos + ')" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-sm btn-dark">Add/Change Image</button>';
                    ctrl.herramientas[cantidadObjetos].html_view = '<img id="image_display_' + cantidadObjetos + '" class="img-thumbnail" ng-src="Imagenes/default_img.png" style="width: 200px; height: 200px;" alt="user_image">';
                    ctrl.herramientas[cantidadObjetos].label = 'image';
                    ctrl.herramientas[cantidadObjetos].ancho = ctrl.ancho;
                    ctrl.herramientas[cantidadObjetos].alto = ctrl.alto;
                    ctrl.imagenActiva = cantidadObjetos;
                    break;
            
                default:                            
                    break;
            }


        }
        
        
        ctrl.getString = function(obj){
            return $sce.trustAsHtml(obj.html_tutorial);
        }

        ctrl.openLink = function(link){
            $window.open('http://' + link, '_blank');
        }

        ctrl.guardar = function(opcion){
            
            ctrl.tutorial = {
                'titulo': ctrl.titulo,
                'descripcion': ctrl.descripcion,
                'imagenTutorial':ctrl.imagen,
                'etiquetas': ctrl.etiquetas,
                'herramientas':ctrl.herramientas,
                'estado': opcion == 1? 'guardado' : 'publicado',
                'id_tutorial': ctrl.id_tutorial
            };


            //guardar imagenes
            //ctrl.uploadFiles();
            
            //guardar informacion
            $http.post('api/loadTutorial',ctrl.tutorial)
            .then(function(response){

                alert('se guardo el tutorial con exito');
                if(ctrl.id_tutorial == undefined){
                    ctrl.id_tutorial = response.data;
                    console.log('el id del tutorial es ' + ctrl.id_tutorial);
                }
            })
            .catch(function(response){
                alert('no se guardo el tutorial con exito');
            });
        
        }

        ctrl.imgActiva = function(num){
            ctrl.imagenActiva = num;
        }

        ctrl.cargarFoto = function(){
            
            var file = document.getElementById('imgToLoad');
            var imgDisplay = 'image_display_' + ctrl.imagenActiva;
            var img = document.getElementById(imgDisplay);
            img.src = URL.createObjectURL(file.files[0]);
            img.style["width"] = ctrl.ancho + 'px';
            img.style["height"] = ctrl.alto + 'px';
            
            var key = ctrl.imagenActiva.toString();
            ctrl.archivosImagenes[key] = file.files[0];            
            ctrl.herramientas[ctrl.imagenActiva].valor = file.files[0].name;
            ctrl.herramientas[ctrl.imagenActiva].ancho = ctrl.ancho;
            ctrl.herramientas[ctrl.imagenActiva].alto = ctrl.alto;

        }

        ctrl.uploadFiles = function(){

            var form_data = new FormData();
            var file = document.getElementById('imageFileTutorial');
            
            if(file.files[0]!= undefined){
                form_data.append('file[]',file.files[0]);
                ctrl.imagen = file.files[0].name;
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
				$location.url('/landing');
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



// app.directive('customOnChange', function() {
//     return {
//       restrict: 'A',
//       link: function (scope, element, attrs) {
//         var onChangeHandler = scope.$eval(attrs.customOnChange);
//         element.on('change', onChangeHandler);
//         element.on('$destroy', function() {
//           element.off();
//         });
  
//       }
//     };
//   });

// app.directive("fileread", [function () {
//     return {
//         scope: {
//             fileread: "="
//         },
//         link: function (scope, element, attributes) {
//             element.bind("change", function (changeEvent) {
//                 var reader = new FileReader();
//                 reader.onload = function (loadEvent) {
//                     scope.$apply(function () {
//                         scope.fileread = loadEvent.target.result;
//                     });
//                 }
//                 reader.readAsDataURL(changeEvent.target.files[0]);
//             });
//         }
//     }
// }]);+



