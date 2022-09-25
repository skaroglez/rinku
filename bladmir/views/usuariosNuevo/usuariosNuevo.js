/*
|
| Carolina Gonzalez Chavez
| - Controllador de la Vista de usuariosNuevo
|
*/

var app = angular.module('usuariosNuevo', []);

// Controller
app.controller('usuariosNuevoController', ['$scope', '$rootScope', '$state', '$stateParams', '$location', '$util', '$message', '$loading', '$validate', 'ModelService', '$cookieStore',
  function ($scope, $rootScope, $state, $stateParams, $location, $util, $message, $loading, $validate, ModelService, $cookieStore) {

    // Scope Variables
    $scope.usuario = { id_rol: "", id_genero: "" };
    $scope.roles = [];
    $scope.generos = [];
    $scope.partidos = [];

    $scope.flags = {
      editar: false
    };

    // Scope Functions
    $scope.regresar = function () {
      $state.go('usuarios');
    };

    $scope.submit = function () {
      $scope.guardar();
    };

    $scope.guardar = function () {

      if (!$validate.form('form-validate'))
        return;

      if ($scope.usuario.vc_password != $scope.usuario.vc_password_re) {
        $message.warning('Las constraseñas proporcionadas no son identicas.');
        return;
      }

      $loading.show();

      if (!$scope.flags.editar) {

        ModelService.add($scope.usuario)
          .success(function () {
            $message.success('El usuario ' + $scope.usuario.vc_nombre + ', fue guardado correctamente.');
            $scope.regresar();
          })
          .error(function (error) {
            if (error.texto) {
              $message.warning(error.texto);
            } else {
              $message.warning('El usuario ' + $scope.usuario.vc_nombre + ', no se pudo agregar correctamente.');
            }
          })
          .finally(function () {
            $loading.hide();
          });
      } else {

        ModelService.update($scope.usuario)
          .success(function () {
            $message.success('El usuario ' + $scope.usuario.vc_nombre + ', fue editado correctamente.');
            $loading.hide();
            $scope.regresar();
          })
          .error(function (error) {
            if (error.texto) {
              $message.warning(error.texto);
            } else {
              $message.warning('El usuario ' + $scope.usuario.vc_nombre + ', no se pudo editar correctamente.');
            }
          })
          .finally(function () {
            $loading.hide();
          });
      }
    };

    $scope.init = function () {

      // Definir Modelo
      ModelService.addModel('usuarios');

      $loading.show();

      ModelService.create()
        .success(function (res) {

          $scope.roles = res.roles;
          $scope.generos = res.generos;

          // Verificar proceso Agregar o Editar
          $util.stateParams(function () {

            $scope.flags.editar = true;

            ModelService.edit($stateParams.id)
              .success(function (res) {
                $scope.usuario = res;
                $scope.usuario.id_rol = String($scope.usuario.id_rol)
                $scope.usuario.id_genero = String($scope.usuario.id_genero)
                $scope.usuario.vc_password_re = angular.copy($scope.usuario.vc_password);
              })
              .error(function (error) {
                if (error.texto) {
                  $message.warning(error.texto);
                } else {
                  $message.warning("No se pudo obtener el registro.");
                }
              })
              .finally(function () {
                $loading.hide();
              });
          });
        })
        .error(function (error) {
          if (error.texto) {
            $message.warning(error.texto);
          } else {
            $message.warning("No se pudo obtener el registro.");
          }
        })
        .finally(function () {
          $loading.hide();
        });
    };

    // Begin Module
    $scope.init();

  }]);