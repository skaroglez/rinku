/*
|
|
| - Controllador Login de la Aplicación
|
*/

+function () {

  var stateProvider = null,
    urlRouterProvider = null;

  var login = angular.module(
    'login',
    [
      'gl.util.factories',
      'gl.util.services',
      'gl.validate.service',
      'gl.authentication.services'
    ]
  );

  login.config(['$httpProvider', function ($httpProvider) {
    $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
  }]);

  /* CONTROLLER */
  login.controller('loginController', ['$scope', '$rootScope', '$location', '$message', '$loading', '$validate', '$authentication', '$window',
    function ($scope, $rootScope, $location, $message, $loading, $validate, $authentication, $window) {

      // Scope Variables
      $scope.usuario = { vc_email: '', vc_password: '' };
      $scope.token = '';

      // Scope Functions
      $scope.login = function (token) {

        if ($scope.token == '')
          return $message.warning('Debe resolver el captcha para poder iniciar sesión.');

        if (!$validate.form('form-validate'))
          return;

        $authentication.login($scope.usuario, function (res) {
          if (res.estatus) {
            localStorage.setItem('pvm.token', res.token);
            window.location.href = "admin";
          } else {
            if (res.texto) {
              $message.warning(res.texto);
            } else {
              $message.warning("No se pudo realizar el inicio de sesión.");
            }
          }
        });
      };

      $scope.setRecaptcha = function (token) {
        $scope.token = token;
      };

      $window.setRecaptcha = $scope.setRecaptcha;


    }]);

}();
