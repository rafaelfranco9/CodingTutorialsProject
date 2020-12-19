angular.module('myApp')
.component('home',{
    templateUrl:'vistas/home.html',
    controller: function($auth,$location,$http){
        ctrl = this;
        
		ctrl.datosUsuario = $auth.getPayload();
	

		ctrl.logout = function () {
			if(confirm("Desea salir del sistema?")){
				$auth.logout();
				$auth.removeToken();
				$location.url('/login');
			}
		}




    }
})