<?php

namespace App;

use App\AutomotorMarca;
use App\AutomotorVersion;
use App\RiesgoAutomotor;

use Illuminate\Database\Eloquent\Model;

class AutomotorModelo extends Model
{
    protected $guarded = [];

    public function automotor_version()
    {
    return $this->hasMany(AutomotorVersion::class, 'automotor_modelo_id', 'id');
    }

    public function automotor_marca() {
    return $this->belongsTo(AutomotorMarca::class, 'automotor_marca_id');
    }

    public function riesgo_automotor(){
    return $this->hasMany(RiesgoAutomotor::class, 'automotor_modelo_id', 'id');
    }
}
