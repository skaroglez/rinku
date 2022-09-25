<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleados extends BaseModel
{
	// Datos Generales
	protected $table = 'empleados';
  protected $fillable = ['nu_numero', 'vc_nombre', 'id_rol', 'id_tipo', 'id_creador'];

  // Relaciones
  public function movimientos()
  {
    return $this->hasOne('App\Models\EmpleadosMovimientos', 'id_empleado');
  }
}
