/*
|
| Carolina Gonzalez Chavez
| - Controllador de la Vista de inicio
|
*/

var app = angular.module('inicio', []);

// Controller
app.controller('inicioController', ['$scope', '$rootScope', '$state', '$stateParams', '$location', '$util', '$message', '$loading', '$validate', 'ModelService', '$cookieStore',
  function ($scope, $rootScope, $state, $stateParams, $location, $util, $message, $loading, $validate, ModelService, $cookieStore) {

    // Scope Variables
    $scope.cargando = false;

    // Scope Funciones
    $scope.actualizar = function () {
      $scope.init();
    };

    $scope.init = function () {      
    };

    // Iniciar Controller
    $scope.init();
  }]);