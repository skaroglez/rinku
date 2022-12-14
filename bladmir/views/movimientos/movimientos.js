/*
|
| Carolina Gonzalez Chavez
| - Controllador de la Vista de Movimientos
|
*/

var app = angular.module('movimientos', []);

// Controller
app.controller('movimientosController', ['$scope', '$rootScope', '$state', '$stateParams', '$location', '$util', '$message', '$loading', '$validate', 'ModelService', '$cookieStore',
  function ($scope, $rootScope, $state, $stateParams, $location, $util, $message, $loading, $validate, ModelService, $cookieStore) {

    // Scope Variables
    $scope.movimientos = [];

    $scope.cargando = false;

    // Scope Funciones
    $scope.actualizar = function () {
      $scope.init();
    };

    $scope.agregar = function () {
      $state.go('movimientosNuevo');
    };

    $scope.editar = function (movimiento) {
      $state.go('movimientosEditar', { id: movimiento.id });
    };

    $scope.eliminar = function (movimiento) {
      $message.confirm({
        text: '¿Estás seguro de eliminar el movimiento?',
        callback: function (msg) {
          $loading.show();
          ModelService.delete(movimiento.id)
            .success(function () {
              msg.close();
              $scope.actualizar();
              $message.success('El movimiento, fue eliminado correctamente.');
            })
            .error(function (error) {
              if (error.texto) {
                $message.warning(error.texto);
              } else {
                $message.warning('El movimiento, no pudo eliminar correctamente.');
              }
            })
            .finally(function () {
              $loading.hide();
            });
        }
      });
    };

    $scope.init = function () {

      ModelService.addModel('movimientos');

      $scope.cargando = true;

      ModelService.list()
        .success(function (res) {
          $scope.movimientos = res;
        })
        .error(function () {
          $message.warning("No se obtuvieron los registros.");
        })
        .finally(function () {
          $scope.cargando = false;
        });
    };

    // Iniciar Controller
    $scope.init();
  }]);