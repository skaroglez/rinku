/*
|
| Carolina Gonzalez Chavez
| - Controllador de la Vista de Usuarios
|
*/

var app = angular.module('usuarios', []);

// Controller
app.controller('usuariosController', ['$scope', '$rootScope', '$state', '$stateParams', '$location', '$util', '$message', '$loading', '$validate', 'ModelService', '$cookieStore',
  function ($scope, $rootScope, $state, $stateParams, $location, $util, $message, $loading, $validate, ModelService, $cookieStore) {

    // Scope Variables
    $scope.usuarios = [];

    $scope.cargando = false;

    // Scope Funciones
    $scope.actualizar = function () {
      $scope.init();
    };

    $scope.agregar = function () {
      $state.go('usuariosNuevo');
    };

    $scope.editar = function (usuario) {
      $state.go('usuariosEditar', { id: usuario.id });
    };

    $scope.eliminar = function (usuario) {
      $message.confirm({
        text: '¿Estás seguro de eliminar el usuario ' + usuario.vc_nombre + ' ' + usuario.vc_apellido + '?',
        callback: function (msg) {
          $loading.show();
          ModelService.delete(usuario.id)
            .success(function () {
              msg.close();
              $scope.actualizar();
              $message.success('El usuario ' + usuario.vc_nombre + ' ' + usuario.vc_apellido + ', fue eliminado correctamente.');
            })
            .error(function (error) {
              if (error.texto) {
                $message.warning(error.texto);
              } else {
                $message.warning('El usuario ' + usuario.vc_nombre + ' ' + usuario.vc_apellido + ', no pudo eliminar correctamente.');
              }
            })
            .finally(function () {
              $loading.hide();
            });
        }
      });
    };

    $scope.init = function () {

      ModelService.addModel('usuarios');

      $scope.cargando = true;

      ModelService.list()
        .success(function (res) {
          $scope.usuarios = res;
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