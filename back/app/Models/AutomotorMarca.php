<?php

namespace App;

use App\AutomotorModelo;
use App\AutomotorVersion;
use App\RiesgoAutomotor;

use Illuminate\Database\Eloquent\Model;

class AutomotorMarca extends Model
{
    protected $guarded = [];

    public function automotor_modelos()
    {
    return $this->hasMany(AutomotorModelo::class, 'automotor_marca_id', 'id');
    }
    
    public function riesgo_automotor(){
    return $this->hasMany(RiesgoAutomotor::class, 'automotor_marca_id', 'id');
    }
    
}
