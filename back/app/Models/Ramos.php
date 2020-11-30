<?php

namespace App\Models;

use App\Models\TipoRiesgo;
use Illuminate\Database\Eloquent\Model;

class Ramo extends Model
{
    public function tipoRiesgo()
    {
        return $this->hasMany(TipoRiesgo::class, 'ramo_id', 'id');
    }
}
