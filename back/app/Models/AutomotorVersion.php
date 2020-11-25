<?php

namespace App;

use App\AutomotorModelo;
use App\AutomotorAnio;
use App\RiesgoAutomotor;

use Illuminate\Database\Eloquent\Model;

class AutomotorVersion extends Model
{
    protected $guarded = [];

    public function automotor_modelo() {
    return $this->belongsTo(AutomotorModelo::class, 'automotor_modelo_id');
    }

    public function automotor_anios()
    {
    return $this->hasMany(AutomotorAnio::class, 'automotor_version_id', 'id');
    }

    public function riesgo_automotor(){
    return $this->hasMany(RiesgoAutomotor::class, 'automotor_version_id', 'id');
    }
}
