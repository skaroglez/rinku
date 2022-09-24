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

use App\Models\Empleados;
use App\Models\EmpleadosRoles;

class EmpleadosController extends Controller
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

    $empleados = [];
    // Obtener empleados del sistema siendo admin
    $empleados = Empleados::join('empleadosRoles as r', 'empleados.id_rol', '=', 'r.id')
      ->where([
        ['empleados.sn_activo', '=', 1],
        ['r.sn_activo',         '=', 1],
        ['empleados.sn_eliminado',  '=', 0],
        ['r.sn_eliminado',        '=', 0]
      ])
      ->whereNull('empleados.dt_eliminado')
      ->whereNull('r.dt_eliminado')
      ->selectRaw('empleados.id, 
      empleados.nu_numero,
      empleados.vc_nombre, 
      empleados.id_tipo, 
      r.vc_nombre as vc_rol')
      ->get();

    return $empleados;
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
    $roles = EmpleadosRoles::where('sn_activo', 1)->where('sn_eliminado', 0)->orderBy('vc_nombre')->get();

    return [
      'roles'   => $roles
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
      'nu_numero'       => 'required',
      'vc_nombre'       => 'required',
      'id_rol'          => 'required',
      'id_tipo'         => 'required',     
    ]);

    if ($validator->fails()) {
      return Response::json(['texto' => 'Actualmente no cuenta con los permisos necesarios.'], 418);
    }

    try {
      DB::beginTransaction();

      // Crear empleado
      $empleado = Empleados::create([
        'nu_numero'   => $body->nu_numero,
        'vc_nombre'   => $body->vc_nombre,
        'id_rol'      => $body->id_rol,
        'id_tipo'     => $body->id_tipo,
        'id_creador'  => $body->usuario->id
      ]);

      DB::commit();
      return ['texto' => 'El empleado ' . $body->vc_nombre . ', fue guardado correctamente.'];
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

    // Obtener empleado
    $empleado = Empleados::join('empleadosRoles as r', 'empleados.id_rol', '=', 'r.id')
    ->where([
      ['empleados.sn_activo', '=', 1],
      ['r.sn_activo',         '=', 1],
      ['empleados.sn_eliminado',  '=', 0],
      ['r.sn_eliminado',        '=', 0]
    ])
    ->whereNull('empleados.dt_eliminado')
    ->whereNull('r.dt_eliminado')
    ->selectRaw('empleados.id, 
    empleados.nu_numero,
    empleados.vc_nombre, 
    empleados.id_tipo,
    empleados.id_rol,
    r.vc_nombre as vc_rol')
    ->findOrFail($id);

    return $empleado;
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
      'nu_numero'       => 'required',
      'vc_nombre'       => 'required',
      'id_rol'          => 'required',
      'id_tipo'         => 'required',     
    ]);


    if ($validator->fails()) {
      return Response::json(['texto' => 'Actualmente no cuenta con los permisos necesarios.'], 418);
    }

    try {
      DB::beginTransaction();

      //Verifica si existe el correo en la BD
      if (Empleados::Filtro()->where('nu_numero', $body->nu_numero)->where('id', '!=', $id)->count() > 0) {
        throw new Exception('El número ' . $body->nu_numero . ', ya se encuentra registrado.', 418);
      } else {

        // Obtener el Empleado
        $empleado = Empleados::where([ ['empleados.sn_activo', '=', 1],['empleados.sn_eliminado',  '=', 0] ])
        ->whereNull('empleados.dt_eliminado')
        ->selectRaw('empleados.id, 
          empleados.nu_numero,
          empleados.vc_nombre, 
          empleados.id_tipo,
          empleados.id_rol')
        ->findOrFail($id);

        

        $empleado->nu_numero  = $body->nu_numero;
        $empleado->vc_nombre  = $body->vc_nombre;
        $empleado->id_tipo    = $body->id_tipo;
        $empleado->id_rol     = $body->id_rol;
        $empleado->save();
      }

      DB::commit();
      return ['texto' => 'El empleado ' . $body->vc_nombre . ', fue actualizado correctamente.'];
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

      // Eliminar empleado
      $empleado = Empleados::Filtro()->findOrFail($id);

      $empleado->forceDelete();

      DB::commit();
      return ['texto' => 'El empleado ' . $empleado->vc_nombre . ', fue eliminado correctamente.'];
    } catch (Exception $e) {
      DB::rollBack();
      return Response::json(['texto' => 'El empleado, no se pudo eliminar correctamente.'], 418);
    }
  }
}
