angular.module('myApp', [ 'ngRoute', 'satellizer','ngSanitize'])
/* Configuración de rutas */
.config(function($routeProvider) { 
	$routeProvider
		.when("/", {
			template: '<landing></landing>',
		})
		.when("/landing",{
			template: '<landing></landing>',
		})
		.when("/login", {
			template: '<login></login>',
			resolve: {
				necesitaLogin: saltarSiLogueado
			},
		})
		.when("/signUp", {
			template: '<sign-up></sign-up>',
		})
		.when("/home",{
			template: '<home></home>',
			resolve: {
				necesitaLogin: loginRequerido
			},
		})
		.when("/showTutorial",{
			template: '<show-tutorial></show-tutorial>',
			resolve: {
				necesitaLogin: loginRequerido
			},
		})
		.when("/profile",{
			template: '<profile></profile>',
			resolve: {
				necesitaLogin: loginRequerido
			},
		})
		.when("/tutorial",{
			template: '<tutorial></tutorial>',
			resolve: {
				necesitaLogin: loginRequerido
			},
		})
		.when("/404", {
			templateUrl: 'vistas/404.html',
		})
		.otherwise('/404');
		;

		function saltarSiLogueado($q, $auth, $location) {
			var deferred = $q.defer();
			if ($auth.isAuthenticated()) {
				$location.path('/home');
				deferred.reject();
			} else {
				deferred.resolve();
			}
			return deferred.promise;
	    };

	    function loginRequerido($q, $auth, $location) {
			var deferred = $q.defer();
			if ($auth.isAuthenticated()) {
				deferred.resolve();
			} else {
				$location.path('/login');
				deferred.reject();
			}
			return deferred.promise;
		}
})
/* Configuración de login */
.config(function($authProvider) {
	var rutaRelativa = window.location.pathname.substr(0,window.location.pathname.lastIndexOf('/'))+'/'; 
    $authProvider.baseUrl = rutaRelativa;
	$authProvider.loginUrl = 'api/login';
})
;
