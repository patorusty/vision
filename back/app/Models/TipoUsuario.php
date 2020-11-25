<?php

namespace App;

use App\Usuarios;
use Illuminate\Database\Eloquent\Model;

class TipoUsuario extends Model
{
    public function usuarios(){
        return $this->hasMany(Usuarios::class, 'tipo_usuario_id', 'id');
    }
}
