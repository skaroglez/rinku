<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpleadosMovimientos extends BaseModel
{
  // Datos Generales
  protected $table = 'empleadosMovimientos';
  protected $fillable = ['id_empleado', 'dt_fecha', 'sn_cubrio_turno', 'id_rol' , 'nu_entregas', 'nu_horas_extras', 'id_creador'];

  // Relaciones

  public function empleado()
  {
    return $this->belongsTo('App\Models\Empleados', 'id_empleado');
  }

}
