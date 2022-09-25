/*
|
|
| - Controllador Admin de la Aplicación
|
*/

+function(){

var stateProvider = null,
	urlRouterProvider = null;

var admin = angular.module(
	'admin',
	[
		'ui.router',
		'oc.lazyLoad',
		'gl.util.factories',
		'gl.util.services',
    'gl.authentication.services',
		'gl.menu.factories',
		'gl.validate.service',
		'gl.interceptor.factories',
		'mwl.calendar',
		'ui.tinymce',
		'ngCookies'
	]
);

admin.config(['$stateProvider', '$urlRouterProvider', '$httpProvider', '$interpolateProvider',
	function($stateProvider, $urlRouterProvider, $httpProvider, $interpolateProvider){

		$interpolateProvider.startSymbol('[[').endSymbol(']]');

		stateProvider = $stateProvider;
		urlRouterProvider = $urlRouterProvider;

		$httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';

		$httpProvider.interceptors.push('$httpInterceptor');
}]);

admin.run( [ '$rootScope', '$state', '$location', '$util', '$menu', '$authentication',
	function( $rootScope, $state, $location, $util, $menu, $authentication ){

		// Request User
		$authentication.check( function( res ){
			console.log(res.usuario);
			if( !res || ($authentication.usuario.rol.id != 2 && $authentication.usuario.rol.id != 3) ){
				//window.location.href = 'login';
			} else {
				$rootScope.usuario = $authentication.usuario;
				// console.log($rootScope.usuario);
				init();
			}
		});

    var states = [];

	  // INIT
    var init = function(){

			switch( $rootScope.usuario.rol.id ){
				case 2 : // Adminstrador
					states = [].concat.call( $menu.general, $menu.admin.states );
					$rootScope.usuario.menu = [].concat.call( $menu.admin.navigation );
				break;
				case 3 : // Encargado de Nomina
					states = [].concat.call( $menu.general, $menu.nomina.states );
					$rootScope.usuario.menu = [].concat.call( $menu.nomina.navigation );
				break;
			}

			var url = $location.path().replace('/', ''),
			position = $util.getPosition( states, 'url', url ),
			state = position ? states[position].state : 'inicio' ;

			initState(function(){
				$state.go( state );
			});
		};

		var initState = function( callback ){

		// Create State Database
		states.forEach(function( state, index, array ){
			stateProvider.state( state.state, {
				url: '/'+state.url,
				templateUrl: 'bladmir/views/'+state.file+'/'+state.file+'.'+state.ext,
				resolve: {
					include: function( $ocLazyLoad ){
						return $ocLazyLoad.load({
							name: state.state,
							files: [
								'bladmir/views/'+state.file+'/'+state.file+'.js',
								'bladmir/views/'+state.file+'/'+state.file+'.css'
							]
						})
					}
				}
			});
		});

    urlRouterProvider.otherwise('/inicio');
    callback();
	};
}]);

/* CONTROLLER */
admin.controller( 'adminController', ['$scope', '$rootScope', '$state', '$location', '$loading', '$message', '$authentication',
	function( $scope, $rootScope, $state, $location, $loading, $message, $authentication ){

    $scope.lock = $loading.status;
    $scope.bodyClass = '';
    $scope.login = {};
    $scope.menu = {};

	  // RootScope
		$rootScope.$on('$stateChangeStart', function( event, next, current ){
			$scope.bodyClass = 'normal';
			$scope.login = $rootScope.usuario;
			$scope.menu = $rootScope.usuario.menu[0];
		});

		$scope.inicio = function(){
			$state.go('inicio');
		};

    $scope.logout = function(){
    	$authentication.logout( function( res ){
    		if( res ){
    			window.location.href = 'login';
    			localStorage.removeItem('pvm.token');
    		} else {
    			$message.warning('No se pudo realizar el cierre de sesión.');
    		}
    	});
    };

    $scope.perfil = function(){

    };

    $scope.goto = function( url ){
    	window.location.href = url;
    };

    $scope.nightMode = function() {
      if ($('body').hasClass('night')) {
        $('body').removeClass('night');
      } else {
        $('body').addClass('night');
      }
    };
}]);

}();
