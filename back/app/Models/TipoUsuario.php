<?php

namespace App\Models;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Model;

class TipoUsuario extends Model
{
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'tipo_usuario_id', 'id');
    }
}
