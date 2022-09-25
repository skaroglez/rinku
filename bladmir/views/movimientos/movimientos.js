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

    $scope.editar = function (empleado) {
      $state.go('movimientosEditar', { id: empleado.id });
    };

    $scope.eliminar = function (empleado) {
      $message.confirm({
        text: '¿Estás seguro de eliminar el empleado ' + empleado.vc_nombre + '?',
        callback: function (msg) {
          $loading.show();
          ModelService.delete(empleado.id)
            .success(function () {
              msg.close();
              $scope.actualizar();
              $message.success('El empleado ' + empleado.vc_nombre + ', fue eliminado correctamente.');
            })
            .error(function (error) {
              if (error.texto) {
                $message.warning(error.texto);
              } else {
                $message.warning('El empleado ' + empleado.vc_nombre + ', no pudo eliminar correctamente.');
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