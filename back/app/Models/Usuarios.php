<?php

namespace App;

use App\TipoUsuario;
use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    protected $guarded = [];

    public function tipo_usuario() {
        return $this->belongsTo(TipoUsuario::class, 'tipo_usuario_id');
    }
}
