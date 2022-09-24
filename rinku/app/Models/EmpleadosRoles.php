<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpleadosRoles extends BaseModel
{
  // Datos Generales
  protected $table = 'empleadosRoles';
  protected $fillable = ['vc_nombre', 'id_creador'];
}
