<?php

namespace App\Models;

use App\Models\Anio;
use App\Models\AutomotorModelo;
use App\Models\RiesgoAutomotor;
use Illuminate\Database\Eloquent\Model;

class AutomotorVersion extends Model
{
    protected $guarded = [];
    protected $table = "automotor_versions";

    public function modelo()
    {
        return $this->belongsTo(AutomotorModelo::class, 'automotor_modelo_id');
    }

    public function anios()
    {
        return $this->belongsToMany(Anio::class, 'anio_version', 'automotor_version_id', 'anio_id');
    }

    public function riesgo_automotor()
    {
        return $this->hasMany(RiesgoAutomotor::class, 'automotor_version_id', 'id');
    }
}
