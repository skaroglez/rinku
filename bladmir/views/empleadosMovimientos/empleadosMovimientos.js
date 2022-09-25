/*
|
| Carolina Gonzalez Chavez
| - Controllador de la Vista de EmpleadosMovimientos
|
*/

var app = angular.module('empleadosMovimientos', []);

// Controller
app.controller('empleadosMovimientosController', ['$scope', '$rootScope', '$state', '$stateParams', '$location', '$util', '$message', '$loading', '$validate', 'ModelService', '$cookieStore',
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
        text: '¿Estás seguro de eliminar el movimiento ' + movimiento.vc_nombre + '?',
        callback: function (msg) {
          $loading.show();
          ModelService.delete(movimiento.id)
            .success(function () {
              msg.close();
              $scope.actualizar();
              $message.success('El movimiento ' + movimiento.vc_nombre + ', fue eliminado correctamente.');
            })
            .error(function (error) {
              if (error.texto) {
                $message.warning(error.texto);
              } else {
                $message.warning('El movimiento ' + movimiento.vc_nombre + ', no pudo eliminar correctamente.');
              }
            })
            .finally(function () {
              $loading.hide();
            });
        }
      });
    };

    $scope.init = function () {

      ModelService.addModel('empleadosMovimientos');

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