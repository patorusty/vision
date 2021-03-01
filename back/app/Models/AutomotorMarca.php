<?php

namespace App\Models;

use App\Models\AutomotorModelo;
use App\Models\RiesgoAutomotor;
use Illuminate\Database\Eloquent\Model;

class AutomotorMarca extends Model
{
    protected $guarded = [];

    public function modelos()
    {
        return $this->hasMany(AutomotorModelo::class, 'automotor_marca_id', 'id');
    }

    public function riesgo_automotor()
    {
        return $this->hasMany(RiesgoAutomotor::class, 'automotor_marca_id', 'id');
    }
}
