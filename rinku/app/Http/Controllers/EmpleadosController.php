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

  public function obtenerSueldo($id){
    // Verificación para el uso del Controllador
    $body = (object)Request::all();
    if (!$this->validateController($body->usuario)) {
      return Response::json(['texto' => 'Actualmente no cuenta con los permisos necesarios.'], 418);
    }

    $empleado = Empleados::Filtro()->findOrFail($id);
    $movimientos = Empleados::join('empleadosMovimientos as em', 'empleados.id', '=', 'em.id_empleado')
    ->where([
      ['empleados.sn_activo', '=', 1],
      ['empleados.sn_eliminado',  '=', 0],
      ['em.sn_activo', '=', 1],
      ['em.sn_eliminado',  '=', 0],
    ])
    ->whereNull('empleados.dt_eliminado')
    ->whereNull('em.dt_eliminado')
    ->where('empleados.id', $id)
    ->selectRaw('MONTH(em.dt_fecha) mes, 
    YEAR(em.dt_fecha) anio, 
    SUM(em.nu_entregas) total_entregas, 
    SUM(em.nu_entregas) * 5 nu_total_sueldo_entregas, 
    SUM(em.nu_horas_extras) total_horas_extras, 
    SUM( CASE
        WHEN em.id_rol = 1 THEN 10 * em.nu_horas_extras
        WHEN em.id_rol = 2 THEN 5 * em.nu_horas_extras
        ELSE 0
    END) nu_total_sueldo_bono')    
    ->groupBy('anio', 'mes')
    ->orderBy('mes', 'desc')
    ->orderBy('anio', 'desc')
    ->get();

    $nombresMeses = [
      1 =>	"ENERO",
      2 =>	"FEBRERO",
      3 =>	"MARZO",
      4 =>	"ABRIL",
      5 =>	"MAYO",
      6 =>	"JUNIO",
      7 =>	"JULIO",
      8 =>	"AGOSTO",
      9 =>	"SEPTIEMBRE",
      10 =>	"OCTUBRE",
      11 =>	"NOVIEMBRE",
      12 =>	"DICIEMBRE"
    ];
    
    for ($i=0; $i < count($movimientos); $i++) {
      $total_dias_mes = date("t", mktime(0, 0, 0, $movimientos[$i]->mes, 1, $movimientos[$i]->anio));
      //Base
      $movimientos[$i]->vc_mes = $nombresMeses[$movimientos[$i]->mes];
      $movimientos[$i]->nu_sueldo_base_hora = 30;
      //jornada de 8 horas
      $movimientos[$i]->nu_sueldo_base_por_dia = 30 * 8;
      $movimientos[$i]->nu_sueldo_base_por_mes = $movimientos[$i]->nu_sueldo_base_por_dia * $total_dias_mes;

      //sueldp antes de retener impuestos
      $movimientos[$i]->nu_sueldo_antes_impuestos = $movimientos[$i]->nu_sueldo_base_por_mes + $movimientos[$i]->nu_total_sueldo_entregas + $movimientos[$i]->nu_total_sueldo_bono;
      //Si el empleado es interno, recibe el 4% de su sueldo en vales
      $movimientos[$i]->nu_sueldo_vales_despensa = $empleado->id_tipo == 1 ? bcdiv($movimientos[$i]->nu_sueldo_antes_impuestos * 0.04,'1',2) : 0;
      
      //Retencion ISR
      $movimientos[$i]->nu_cantidad_retenida_isr = $movimientos[$i]->nu_sueldo_antes_impuestos > 16000 ? bcdiv($movimientos[$i]->nu_sueldo_antes_impuestos * 0.12,'1',2) :  bcdiv($movimientos[$i]->nu_sueldo_antes_impuestos * 0.09,'1',2);
      $movimientos[$i]->nu_sueldo_final = bcdiv($movimientos[$i]->nu_sueldo_antes_impuestos - $movimientos[$i]->nu_cantidad_retenida_isr,'1',2);
      $movimientos[$i]->nu_sueldo_final_menos_vales_despensa = bcdiv($movimientos[$i]->nu_sueldo_final - $movimientos[$i]->nu_sueldo_vales_despensa,'1',2);
    }

    return $movimientos;
  }
}
