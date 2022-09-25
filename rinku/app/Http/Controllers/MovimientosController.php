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
use App\Models\EmpleadosMovimientos;

class MovimientosController extends Controller
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

    $movimientos = [];
    // Obtener movimientos del sistema
    $movimientos = EmpleadosMovimientos::join('empleados as empleado', 'empleadosMovimientos.id_empleado', '=', 'empleado.id')
    ->join('empleadosRoles as r', 'empleado.id_rol', '=', 'r.id')
    ->where([
      ['empleadosMovimientos.sn_activo', '=', 1],
      ['empleado.sn_activo', '=', 1],
      ['r.sn_activo',         '=', 1],
      ['empleadosMovimientos.sn_eliminado',  '=', 0],
      ['empleado.sn_eliminado',  '=', 0],
      ['r.sn_eliminado',        '=', 0]
    ])
    ->whereNull('empleadosMovimientos.dt_eliminado')
    ->whereNull('empleado.dt_eliminado')
    ->whereNull('r.dt_eliminado')
    ->selectRaw('empleadosMovimientos.id_empleado, 
              empleadosMovimientos.dt_fecha, 
              empleadosMovimientos.sn_cubrio_turno, 
              empleadosMovimientos.id_rol, 
              empleadosMovimientos.nu_entregas, 
              empleadosMovimientos.nu_horas_extras,
              empleado.nu_numero,
              empleado.vc_nombre, 
              empleado.id_tipo, 
              empleado.id_rol,
              r.vc_nombre as vc_rol')
    ->get();

    return $movimientos;
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

    // Obtener todos los Empleados
    $empleados = Empleados::join('empleadosRoles as r', 'empleados.id_rol', '=', 'r.id')
    ->where('empleados.sn_activo', 1)->where('empleados.sn_eliminado', 0)
    ->where('r.sn_activo', 1)->where('r.sn_eliminado', 0)
    ->whereNull('empleados.dt_eliminado')->whereNull('r.dt_eliminado')
    ->selectRaw('empleados.id, 
              empleados.nu_numero,
              empleados.vc_nombre, 
              empleados.id_tipo, 
              empleados.id_rol, 
              r.vc_nombre as vc_rol')
    ->orderBy('empleados.vc_nombre')->get();

    // Obtener todos los Roles
    $roles = EmpleadosRoles::where('sn_activo', 1)->where('sn_eliminado', 0)->orderBy('vc_nombre')->get();

    return [
      'empleados' => $empleados,
      'roles'     => $roles
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
      'dt_fecha' => 'required',
      'id_empleado' => 'required',
      'id_rol' =>'required',
      'nu_entregas' => 'required',
      'nu_horas_extras' => 'required',
      'sn_cubrio_turno' => 'required',    
    ]);

    if ($validator->fails()) {
      return Response::json(['texto' => 'Actualmente no cuenta con los permisos necesarios.'], 418);
    }

    try {
      DB::beginTransaction();

      // Crear empleado
      $empleado = EmpleadosMovimientos::create([
        'dt_fecha'        => date('Y-m-d', strtotime($body->dt_fecha)),
        'id_empleado'     => $body->id_empleado,
        'id_rol'          => $body->id_rol,
        'nu_entregas'     => $body->nu_entregas,
        'nu_horas_extras' => $body->nu_horas_extras,
        'sn_cubrio_turno' => $body->sn_cubrio_turno,
        'id_creador'      => $body->usuario->id
      ]);

      DB::commit();
      return ['texto' => 'El movimiento, fue guardado correctamente.'];
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
    $movimiento = EmpleadosMovimientos::join('empleados as empleado', 'empleadosMovimientos.id_empleado', '=', 'empleado.id')
    ->join('empleadosRoles as r', 'empleado.id_rol', '=', 'r.id')
    ->where([
      ['empleadosMovimientos.sn_activo', '=', 1],
      ['empleado.sn_activo', '=', 1],
      ['r.sn_activo',         '=', 1],
      ['empleadosMovimientos.sn_eliminado',  '=', 0],
      ['empleado.sn_eliminado',  '=', 0],
      ['r.sn_eliminado',        '=', 0]
    ])
    ->whereNull('empleadosMovimientos.dt_eliminado')
    ->whereNull('empleado.dt_eliminado')
    ->whereNull('r.dt_eliminado')
    ->selectRaw('empleadosMovimientos.id_empleado, 
              empleadosMovimientos.dt_fecha, 
              empleadosMovimientos.sn_cubrio_turno, 
              empleadosMovimientos.id_rol, 
              empleadosMovimientos.nu_entregas, 
              empleadosMovimientos.nu_horas_extras,
              empleado.nu_numero,
              empleado.vc_nombre, 
              empleado.id_tipo, 
              empleado.id_rol,
              r.vc_nombre as vc_rol')
    ->findOrFail($id);

    return $movimiento;
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
