<?php

namespace App\Models;

use App\Models\Anio;
use App\Models\AutomotorModelo;
use App\Models\RiesgoAutomotor;
use Illuminate\Database\Eloquent\Model;

class AutomotorVersion extends Model
{
    protected $fillable = ['nombre', "automotor_modelo_id", 'updated_at', 'created_at'];
    protected $table = "automotor_versiones";

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
