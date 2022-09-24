<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuariosDetalles extends BaseModel
{
  // Datos Generales
  protected $table = 'usuariosDetalles';
  protected $fillable = ['id_usuario', 'id_genero', 'vc_nombre', 'vc_apellidos' , 'vc_email', 'vc_password', 'id_creador'];

  // Relaciones

  public function usuario()
  {
    return $this->belongsTo('App\Models\Usuarios', 'id_usuario');
  }

}
