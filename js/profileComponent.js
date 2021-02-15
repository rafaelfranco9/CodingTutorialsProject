angular.module("myApp")
.component("profile",{
    templateUrl:'vistas/profile.html',
    controller: function($http,$location,$rootScope,$auth){
        ctrl = this;

        ctrl.$onInit = function(){
            ctrl.tutoriales = [];
            ctrl.copiaDataUsuario = {};
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
                alert('no funciona tutoriales');
            });

        }

        ctrl.buscarInfoUsuario = function(){

            $http.get('api/ProfileInfo')
            .then(function(response){
                ctrl.dataUsuario = response.data;
                if(ctrl.dataUsuario.imagen != 'Imagenes/default_profile.png'){
                    ctrl.imagenAnterior = ctrl.dataUsuario.imagen;
                }
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

        ctrl.editarPerfil = function(){
            ctrl.copiaDataUsuario = Object.assign({},ctrl.dataUsuario);
        }
        
        ctrl.guardarCambiosPerfil = function(){

            ctrl.dataUsuario = ctrl.copiaDataUsuario;
            ctrl.uploadFiles();
            borrarFotoAnterior();
            $http.post('api/UserData',ctrl.dataUsuario)
            .then(function(response){
                location.reload();
                alert('datos actualizados con exito');
            })
            .catch(function(response){
                alert('ocurrio un problema actualizando los datos');
            });
            
        }

        function borrarFotoAnterior(){
           
            if(ctrl.dataUsuario.imagen != 'Imagenes/default_profile.png' && ctrl.imagenAnterior != ctrl.dataUsuario.imagen){
                $http.delete('api/LastProfilePic',{data:({imagen: ctrl.imagenAnterior})})
                .then(function(response){
                    
                })
                .catch(function(response){
    
                });
            }

        }

        ctrl.cargarFoto = function(){
            
            var file = document.getElementById('imgToLoad');
            if(file.files[0]!= undefined){
                ctrl.dataUsuario.imagen = URL.createObjectURL(file.files[0]);
            }
        }

        ctrl.uploadFiles = function(){

            var form_data = new FormData();
            var file = document.getElementById('imgToLoad');
        
            if(file.files[0]!= undefined){
                ctrl.dataUsuario.imagen = 'usuarios/USER_' + ctrl.dataUsuario.id + '/imagenes/' + file.files[0].name;
                form_data.append('file[]',file.files[0]);
                $http.post('api/loadProfilePicture',form_data,
                {
                    transformRequest:angular.identity,
                    headers: {'Content-Type':undefined,'Process-Data':false}
                }).then(function(response){
                    
                });
            }

        }

    },
});