/*
|
| Carolina Gonzalez Chavez
| - Controllador de la Vista de movimientosNuevo
|
*/

var app = angular.module('movimientosNuevo', []);

// Controller
app.controller('movimientosNuevoController', ['$scope', '$rootScope', '$state', '$stateParams', '$location', '$util', '$message', '$loading', '$validate', 'ModelService', '$cookieStore',
  function ($scope, $rootScope, $state, $stateParams, $location, $util, $message, $loading, $validate, ModelService, $cookieStore) {

    // Scope Variables
    $scope.movimiento = { id_empleado: "", id_rol: "", dt_fecha:"", sn_cubrio_turno: 0, nu_entregas:0, nu_horas_extras:0 };
    $scope.roles = []; 
    $scope.empleados = [];
    $scope.empleadoSeleccionado = {vc_rol:""};
    $scope.flags = {
      editar: false
    };

    // Scope Functions
    $scope.regresar = function () {
      $state.go('movimientos');
    };

    $scope.submit = function () {
      $scope.guardar();
    };

    $scope.guardar = function () {

      if (!$validate.form('form-validate'))
        return;

      if (!$scope.movimiento.id_rol) {
        $message.warning('Seleccione el rol.');
        return;
      }else if ($scope.movimiento.id_rol == "") {
        $message.warning('Seleccione el rol.');
        return;
      }


      $loading.show();

      if (!$scope.flags.editar) {

        ModelService.add($scope.movimiento)
          .success(function () {
            $message.success('El movimiento, fue guardado correctamente.');
            $scope.regresar();
          })
          .error(function (error) {
            if (error.texto) {
              $message.warning(error.texto);
            } else {
              $message.warning('El movimiento, no se pudo agregar correctamente.');
            }
          })
          .finally(function () {
            $loading.hide();
          });
      } else {

        ModelService.update($scope.movimiento)
          .success(function () {
            $message.success('El movimiento, fue editado correctamente.');
            $loading.hide();
            $scope.regresar();
          })
          .error(function (error) {
            if (error.texto) {
              $message.warning(error.texto);
            } else {
              $message.warning('El movimiento, no se pudo editar correctamente.');
            }
          })
          .finally(function () {
            $loading.hide();
          });
      }
    };

    $scope.empleadoChange = function () {
      $scope.empleadoSeleccionado = $scope.empleados.filter( (empleado) => {
        return empleado.id == $scope.movimiento.id_empleado;
      });
      $scope.empleadoSeleccionado = $scope.empleadoSeleccionado[0];
      $scope.movimiento.id_rol = $scope.empleadoSeleccionado.id_rol;
    }
    $scope.cambiar_sn_cubrio_turno = function(value){      
      $scope.movimiento.sn_cubrio_turno = value;
    }

    $scope.init = function () {

      // Definir Modelo
      ModelService.addModel('movimientos');

      $loading.show();

      ModelService.create()
        .success(function (res) {

          $scope.roles = res.roles;
          $scope.empleados = res.empleados;

          // Verificar proceso Agregar o Editar
          $util.stateParams(function () {

            $scope.flags.editar = true;

            ModelService.edit($stateParams.id)
              .success(function (res) {
                $scope.movimiento = res;
                $scope.movimiento.id_rol = String($scope.movimiento.id_rol);
                $scope.movimiento.id_tipo = String($scope.movimiento.id_tipo);
                $scope.movimiento.vc_password_re = angular.copy($scope.movimiento.vc_password);
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