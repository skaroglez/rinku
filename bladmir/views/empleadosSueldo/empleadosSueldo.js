/*
|
| Carolina Gonzalez Chavez
| - Controllador de la Vista de empleadosSueldo
|
*/

var app = angular.module('empleadosSueldo', []);

// Controller
app.controller('empleadosSueldoController', ['$scope', '$rootScope', '$state', '$stateParams', '$location', '$util', '$message', '$loading', '$validate', 'ModelService', '$cookieStore', '$http',
  function ($scope, $rootScope, $state, $stateParams, $location, $util, $message, $loading, $validate, ModelService, $cookieStore, $http) {

    // Scope Variables
    $scope.empleado = { id_rol: "", id_tipo: "" };
    $scope.roles = [];
    $scope.movimientosMeses = [];

    $scope.flags = {
      editar: false
    };

    // Scope Functions
    $scope.regresar = function () {
      $state.go('empleados');
    };

    $scope.submit = function () {
      $scope.guardar();
    };

    $scope.guardar = function () {

      if (!$validate.form('form-validate'))
        return;

      if ($scope.empleado.vc_password != $scope.empleado.vc_password_re) {
        $message.warning('Las constraseñas proporcionadas no son identicas.');
        return;
      }

      $loading.show();

      if (!$scope.flags.editar) {

        ModelService.add($scope.empleado)
          .success(function () {
            $message.success('El empleado ' + $scope.empleado.vc_nombre + ', fue guardado correctamente.');
            $scope.regresar();
          })
          .error(function (error) {
            if (error.texto) {
              $message.warning(error.texto);
            } else {
              $message.warning('El empleado ' + $scope.empleado.vc_nombre + ', no se pudo agregar correctamente.');
            }
          })
          .finally(function () {
            $loading.hide();
          });
      } else {

        ModelService.update($scope.empleado)
          .success(function () {
            $message.success('El empleado ' + $scope.empleado.vc_nombre + ', fue editado correctamente.');
            $loading.hide();
            $scope.regresar();
          })
          .error(function (error) {
            if (error.texto) {
              $message.warning(error.texto);
            } else {
              $message.warning('El empleado ' + $scope.empleado.vc_nombre + ', no se pudo editar correctamente.');
            }
          })
          .finally(function () {
            $loading.hide();
          });
      }
    };

    $scope.init = function () {

      // Definir Modelo
      ModelService.addModel('empleados');

      $loading.show();

      ModelService.create()
        .success(function (res) {

          $scope.roles = res.roles;

          // Verificar proceso Agregar o Editar
          $util.stateParams(function () {

            $scope.flags.editar = true;

            ModelService.edit($stateParams.id)
              .success(function (res) {
                $scope.empleado = res;
                $scope.empleado.id_rol = String($scope.empleado.id_rol);
                $scope.empleado.id_tipo = String($scope.empleado.id_tipo);
                $scope.empleado.vc_password_re = angular.copy($scope.empleado.vc_password);



                $loading.show();
                $http.get('api/empleados/'+$stateParams.id+'/sueldo')
                .success(function(res){
                   $scope.movimientosMeses =res;
                   
                })
                .error(function (error) {
                    if(error.texto){
                        $message.warning(error.texto);
                    } else {
                        $message.warning("No se pudo obtener el registro. #LOT01");
                    }
                })
                .finally(function(){
                    $loading.hide();
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