<?php

namespace App\Models;

use App\Models\Clientes;
use App\Models\CodigoProductor;
use Illuminate\Database\Eloquent\Model;

class Productor extends Model
{
    protected $fillable = ['nombre', 'apellido', 'cuit', 'matricula', 'email', 'telefono_1', 'telefono_2', "activo"];

    public function clientes()
    {
        return $this->hasMany(Clientes::class, 'productor_id', 'id');
    }

    public function codigo_productor()
    {
        return $this->hasMany(CodigoProductor::class, 'productor_id', 'id');
    }

    protected $table = 'productores';
}
