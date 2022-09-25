<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends BaseModel
{
	// Datos Generales
	protected $table = 'usuarios';
  protected $fillable = ['id_creador'];

  // Relaciones
  public function rol()
  {
    return $this->hasOne('App\Models\UsuariosRoles', 'id_usuario');
  }
  public function detalle()
  {
    return $this->hasOne('App\Models\UsuariosDetalles', 'id_usuario');
  }
}
