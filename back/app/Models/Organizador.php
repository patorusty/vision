<?php

namespace App\Models;

use App\Models\CodigoOrganizador;
use Illuminate\Database\Eloquent\Model;

class Organizador extends Model
{
    protected $fillable = ['nombre', 'apellido', 'cuit', 'matricula', 'email', 'telefono_1', 'telefono_2', "activo"];

    public function codigo_organizador()
    {
        return $this->hasMany(CodigoOrganizador::class, 'organizador_id', 'id');
    }

    protected $table = 'organizadores';
}
