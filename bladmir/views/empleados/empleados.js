/*
|
| Carolina Gonzalez Chavez
| - Controllador de la Vista de Empleados
|
*/

var app = angular.module('empleados', []);

// Controller
app.controller('empleadosController', ['$scope', '$rootScope', '$state', '$stateParams', '$location', '$util', '$message', '$loading', '$validate', 'ModelService', '$cookieStore',
  function ($scope, $rootScope, $state, $stateParams, $location, $util, $message, $loading, $validate, ModelService, $cookieStore) {

    // Scope Variables
    $scope.empleados = [];

    $scope.cargando = false;

    // Scope Funciones
    $scope.actualizar = function () {
      $scope.init();
    };

    $scope.agregar = function () {
      $state.go('empleadosNuevo');
    };

    $scope.editar = function (empleado) {
      $state.go('empleadosEditar', { id: empleado.id });
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

      ModelService.addModel('empleados');

      $scope.cargando = true;

      ModelService.list()
        .success(function (res) {
          $scope.empleados = res;
        })
        .error(function () {
          $message.warning("No se obtener los registros.");
        })
        .finally(function () {
          $scope.cargando = false;
        });
    };

    // Iniciar Controller
    $scope.init();
  }]);