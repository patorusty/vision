<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ramos extends Model
{
    public function tipoRiesgo()
    {
    return $this->hasMany(TipoRiesgo::class, 'ramo_id', 'id');
    }
}
