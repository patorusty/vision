<?php

namespace App\Models;

use App\Models\TipoUsuario;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $guarded = [];

    public function tipo_usuario()
    {
        return $this->belongsTo(TipoUsuario::class, 'tipo_usuario_id');
    }
}
