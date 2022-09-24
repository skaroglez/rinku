<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Hash;
use Request;
use Cookie;
use Session;
use Response;
use Exception;
use Validator;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

use App\Models\Usuarios;
use App\Models\UsuariosRoles;
use App\Models\UsuariosDetalles;
use App\Models\UsuariosTokens;
use App\Models\Roles;
use App\Models\Generos;

class UsuariosController extends Controller
{
  /**
   * Validate a new controller instance.
   *
   * @param  UserRepository  $usuario
   * @return void
   */
  public function validateController($usuario)
  {
    // Validar parametros de session necesarios
    if (!isset($usuario->rol->id)) {
      return false;
    }

    // Validar acceso permitido por Roles
    if ($usuario->rol->id_rol != 2) {
      return false;
    }

    return true;
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    // Verificación para el uso del Controllador
    $body = (object)Request::all();
    if (!$this->validateController($body->usuario)) {
      return Response::json(['texto' => 'Actualmente no cuenta con los permisos necesarios.'], 418);
    }

    $usuarios = [];
    // Obtener usuarios del sistema siendo admin
    $usuarios = Usuarios::join('usuariosDetalles as ud', 'usuarios.id', '=', 'ud.id_usuario')
      ->join('usuariosRoles as ur', 'usuarios.id', '=', 'ur.id_usuario')
      ->join('roles as r', 'ur.id_rol', '=', 'r.id')
      ->join('generos as g', 'ud.id_genero', '=', 'g.id')
      ->where('usuarios.id', '!=', 1)
      ->where('r.id', '<', 4)
      ->where([
        ['usuarios.sn_activo',    '=', 1],
        ['ud.sn_activo',           '=', 1],
        ['ur.sn_activo',           '=', 1],
        ['r.sn_activo',           '=', 1],
        ['g.sn_activo',           '=', 1],
        ['usuarios.sn_eliminado',  '=', 0],
        ['ud.sn_eliminado',       '=', 0],
        ['ur.sn_eliminado',       '=', 0],
        ['r.sn_eliminado',         '=', 0],
        ['g.sn_eliminado',         '=', 0],
      ])
      ->whereNull('usuarios.dt_eliminado')
      ->whereNull('ud.dt_eliminado')
      ->whereNull('ur.dt_eliminado')
      ->whereNull('r.dt_eliminado')
      ->whereNull('g.dt_eliminado')
      ->selectRaw('usuarios.id, ud.vc_nombre, ud.vc_email, r.vc_nombre as vc_rol, g.vc_nombre as vc_genero, ud.id_genero as id_genero')
      ->get();

    return $usuarios;
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    // Verificación para el uso del Controllador
    $body = (object)Request::all();
    if (!$this->validateController($body->usuario)) {
      return Response::json(['texto' => 'Actualmente no cuenta con los permisos necesarios.'], 418);
    }

    // Obtener todos los Roles
    $roles = Roles::where('id', '>', 1)->where('id', '<', 4)->orderBy('vc_nombre')->get();
    for ($i = 0; $i < count($roles); $i++) {
      $roles[$i] = [
        'id' => $roles[$i]->id,
        'vc_nombre' => $roles[$i]->vc_nombre,
      ];
    }

    // Obtener todos los Generos
    $generos = Generos::orderBy('vc_nombre')->get();
    for ($i = 0; $i < count($generos); $i++) {
      $generos[$i] = [
        'id' => $generos[$i]->id,
        'vc_nombre' => $generos[$i]->vc_nombre,
      ];
    }

    return [
      'roles'   => $roles,
      'generos' => $generos
    ];
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    // Verificación para el uso del Controllador
    $body = (object)Request::all();
    if (!$this->validateController($body->usuario)) {
      return Response::json(['texto' => 'Actualmente no cuenta con los permisos necesarios.'], 418);
    }

    // Validacion de parametros
    $validator = Validator::make((array)$body, [
      'vc_nombre'       => 'required',
      'vc_apellidos'    => 'required',
      'id_rol'          => 'required',
      'id_genero'       => 'required',
      'vc_email'        => 'required',
      'vc_password'     => 'required',
      'vc_password_re'  => 'required'
    ]);

    if ($validator->fails()) {
      return Response::json(['texto' => 'Actualmente no cuenta con los permisos necesarios.'], 418);
    }

    // Validar contraseñas
    if ($body->vc_password != $body->vc_password_re) {
      return Response::json(['texto' => 'Las contraseñas proporcionadas no son identicas.'], 418);
    }

    try {
      DB::beginTransaction();

      // Variables
      $dt_actual = Carbon::now()->format('Y-m-d');

      // Verifica si existe en la BD
      if (UsuariosDetalles::Filtro()->EsEmail($body->vc_email)->count() > 0) {
        throw new Exception('El correo ' . $body->vc_email . ', ya se encuentra registrado.', 418);
      } else {

        // Crear Usuario
        $usuario = Usuarios::create([
          'id_creador'  => $body->usuario->id
        ]);

        // Crear Usuario Roles
        UsuariosRoles::create([
          'id_usuario'   => $usuario->id,
          'id_rol'       => $body->id_rol,
          'id_creador'   => $body->usuario->id
        ]);

        // Crear Usuario Detalles
        UsuariosDetalles::create([
          'id_usuario'      => $usuario->id,
          'vc_nombre'       => $body->vc_nombre,
          'id_genero'       => $body->id_genero,
          'vc_apellidos'    => $body->vc_apellidos,
          'vc_email'        => $body->vc_email,
          'vc_password'     => $body->vc_password,
          'id_creador'      => $body->usuario->id
        ]);
      }

      DB::commit();
      return ['texto' => 'El usuario ' . $body->vc_nombre . ', fue guardado correctamente.'];
    } catch (Exception $e) {
      DB::rollBack();
      return Response::json(['texto' => $e->getMessage()], 418);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    // Verificación para el uso del Controllador
    $body = (object)Request::all();
    if (!$this->validateController($body->usuario)) {
      return Response::json(['texto' => 'Actualmente no cuenta con los permisos necesarios.'], 418);
    }

    // Obtener Usuario
    $usuario = Usuarios::Filtro()->with('detalle', 'rol')->findOrFail($id);

    $usuario = [
      'id'            => $usuario->id,
      'vc_nombre'     => $usuario->detalle->vc_nombre,
      'vc_apellidos'  => $usuario->detalle->vc_apellidos,
      'id_genero'     => $usuario->detalle->id_genero,
      'id_rol'        => $usuario->rol->id_rol,
      'vc_email'      => $usuario->detalle->vc_email,
      'vc_password'   => $usuario->detalle->vc_password,
    ];

    return $usuario;
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
    // Verificación para el uso del Controllador
    $body = (object)Request::all();
    if (!$this->validateController($body->usuario)) {
      return Response::json(['texto' => 'Actualmente no cuenta con los permisos necesarios.'], 418);
    }

    // Validacion de parametros
    $validator = Validator::make((array)$body, [
      'vc_nombre'       => 'required',
      'vc_apellidos'    => 'required',
      'id_genero'       => 'required',
      'vc_email'        => 'required',
      'vc_password'     => 'required',
      'vc_password_re'  => 'required'
    ]);

    if ($validator->fails()) {
      return Response::json(['texto' => 'Actualmente no cuenta con los permisos necesarios.'], 418);
    }

    // Validar contraseñas
    if ($body->vc_password != $body->vc_password_re) {
      return Response::json(['texto' => 'Las contraseñas proporcionadas no son identicas.'], 418);
    }

    try {
      DB::beginTransaction();

      //Verifica si existe el correo en la BD
      if (UsuariosDetalles::Filtro()->EsEmail($body->vc_email)->where('id_usuario', '!=', $id)->count() > 0) {
        throw new Exception('El correo ' . $body->vc_email . ', ya se encuentra registrado.', 418);
      } else {

        // Obtener el Usuario
        $usuario = Usuarios::Filtro()->with('detalle')->findOrFail($id);

        // Obtener el detalle del Usuario
        $usuarioDetalle = UsuariosDetalles::Filtro()->findOrFail($usuario->detalle->id);

        $usuarioRol = UsuariosRoles::Filtro()
          ->where('id_usuario', $usuario->id)
          ->first();

        $usuarioRol->id_rol = $body->id_rol;
        $usuarioRol->save();

        $usuarioDetalle->vc_nombre      = $body->vc_nombre;
        $usuarioDetalle->vc_apellidos   = $body->vc_apellidos;
        $usuarioDetalle->id_genero      = $body->id_genero;
        $usuarioDetalle->vc_email       = $body->vc_email;
        $usuarioDetalle->vc_password    = $body->vc_password;
        $usuarioDetalle->save();
      }

      DB::commit();
      return ['texto' => 'El usuario ' . $body->vc_nombre . ', fue actualizado correctamente.'];
    } catch (Exception $e) {
      DB::rollBack();
      return Response::json(['texto' => $e->getMessage()], 418);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    // Verificación para el uso del Controllador
    $body = (object)Request::all();
    if (!$this->validateController($body->usuario)) {
      return Response::json(['texto' => 'Actualmente no cuenta con los permisos necesarios.'], 418);
    }

    try {
      DB::beginTransaction();

      // Eliminar Usuario
      $usuario = Usuarios::Filtro()->findOrFail($id);

      // Eliminar Usuario Tokens
      $usuarioTokens = UsuariosTokens::Filtro()->where('id_usuario', $usuario->id)->get();
      foreach ($usuarioTokens as $usuarioToken) {
        $usuarioToken->forceDelete();
      }

      // Eliminar Usuario Rol
      $usuarioRol = UsuariosRoles::Filtro()->where('id_usuario', $usuario->id)->first();
      $usuarioRol->forceDelete();

      // Eliminar Usuario Detalle
      $usuarioDetalle = UsuariosDetalles::Filtro()->where('id_usuario', $usuario->id)->first();
      $usuarioDetalle->forceDelete();

      $usuario->forceDelete();

      DB::commit();
      return ['texto' => 'El usuario ' . $usuarioDetalle->vc_nombre . ', fue eliminado correctamente.'];
    } catch (Exception $e) {
      DB::rollBack();
      return Response::json(['texto' => 'El usuario, no se pudo eliminar correctamente.'], 418);
    }
  }
}
