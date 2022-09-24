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
          ],
          navigation : {
            aside: [
              // MENU
                { name: 'Menú', url: '', icon: '', title: 1 },
                  { name: 'Dashboard', url: '#/inicio', icon: 'ti-desktop', title: 0 },
                  { name: 'Usuarios', url: '#/usuarios', icon: 'ti-user', title: 0 },              
              
            ],
          },
        },
      };

      return factory;
  }])
}();
