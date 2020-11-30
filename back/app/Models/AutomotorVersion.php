<?php

namespace App\Models;

use App\Models\AutomotorAnio;
use App\Models\AutomotorModelo;
use App\Models\RiesgoAutomotor;
use Illuminate\Database\Eloquent\Model;

class AutomotorVersion extends Model
{
    protected $guarded = [];

    public function automotor_modelo()
    {
        return $this->belongsTo(AutomotorModelo::class, 'automotor_modelo_id');
    }

    public function automotor_anios()
    {
        return $this->hasMany(AutomotorAnio::class, 'automotor_version_id', 'id');
    }

    public function riesgo_automotor()
    {
        return $this->hasMany(RiesgoAutomotor::class, 'automotor_version_id', 'id');
    }
}
