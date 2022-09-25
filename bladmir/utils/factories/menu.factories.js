/*
|
|
| - Fabricas de Menu
|
*/

+function(){
  angular.module('gl.menu.factories',[])
    .factory('$menu', [ function(){
      var factory = {
        general : [
          // INICIO
            { state: 'inicio', url: 'inicio', file: 'inicio', ext: 'html' },
        ],
        admin : {
          states: [
          // DASHBOARD
            // USUARIOS
              { state: 'usuarios', url: 'usuarios', file: 'usuarios', ext: 'html' },
              { state: 'usuariosNuevo', url: 'usuarios/nuevo', file: 'usuariosNuevo', ext: 'html' },
              { state: 'usuariosEditar', url: 'usuarios/:id/editar', file: 'usuariosNuevo', ext: 'html' },
            // EMPLEADOS
              { state: 'empleados', url: 'empleados', file: 'empleados', ext: 'html' },
              { state: 'empleadosNuevo', url: 'empleados/nuevo', file: 'empleadosNuevo', ext: 'html' },
              { state: 'empleadosEditar', url: 'empleados/:id/editar', file: 'empleadosNuevo', ext: 'html' },
              { state: 'empleadosSueldo', url: 'empleados/:id/sueldo', file: 'empleadosSueldo', ext: 'html' },
            // MOVIMIENTOS DE EMPLEADOS
              { state: 'movimientos', url: 'movimientos', file: 'movimientos', ext: 'html' },
              { state: 'movimientosNuevo', url: 'movimientos/nuevo', file: 'movimientosNuevo', ext: 'html' },
              { state: 'movimientosEditar', url: 'movimientos/:id/editar', file: 'movimientosNuevo', ext: 'html' },
          ],
          navigation : {
            aside: [
              // MENU
                { name: 'Menú', url: '', icon: '', title: 1 },
                  { name: 'Dashboard', url: '#/inicio', icon: 'ti-desktop', title: 0 },
                  { name: 'Usuarios', url: '#/usuarios', icon: 'ti-user', title: 0 },
                  { name: 'Empleados', url: '#/empleados', icon: 'ti-face-smile', title: 0 },
                  { name: 'Movimientos', url: '#/movimientos', icon: 'ti-book', title: 0 },
              
            ],
          },
        },
        nomina : {
          states: [
          // DASHBOARD            
            // EMPLEADOS
              { state: 'empleados', url: 'empleados', file: 'empleados', ext: 'html' },
              { state: 'empleadosNuevo', url: 'empleados/nuevo', file: 'empleadosNuevo', ext: 'html' },
              { state: 'empleadosEditar', url: 'empleados/:id/editar', file: 'empleadosNuevo', ext: 'html' },
              { state: 'empleadosSueldo', url: 'empleados/:id/sueldo', file: 'empleadosSueldo', ext: 'html' },
            // MOVIMIENTOS DE EMPLEADOS
              { state: 'movimientos', url: 'movimientos', file: 'movimientos', ext: 'html' },
              { state: 'movimientosNuevo', url: 'movimientos/nuevo', file: 'movimientosNuevo', ext: 'html' },
              { state: 'movimientosEditar', url: 'movimientos/:id/editar', file: 'movimientosNuevo', ext: 'html' },
          ],
          navigation : {
            aside: [
              // MENU
                { name: 'Menú', url: '', icon: '', title: 1 },
                  { name: 'Dashboard', url: '#/inicio', icon: 'ti-desktop', title: 0 },                
                  { name: 'Empleados', url: '#/empleados', icon: 'ti-face-smile', title: 0 },
                  { name: 'Movimientos', url: '#/movimientos', icon: 'ti-book', title: 0 },
              
            ],
          },
        },
      };

      return factory;
  }])
}();
