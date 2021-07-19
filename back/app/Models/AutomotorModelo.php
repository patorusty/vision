<?php

namespace App\Models;

use App\Models\AutomotorMarca;
use App\Models\AutomotorVersion;
use App\Models\RiesgoAutomotor;
use Illuminate\Database\Eloquent\Model;

class AutomotorModelo extends Model
{
    protected $fillable = ['nombre', "automotor_marca_id", 'updated_at', 'created_at'];

    public function versiones()
    {
        return $this->hasMany(AutomotorVersion::class, 'automotor_modelo_id', 'id');
    }

    public function marca()
    {
        return $this->belongsTo(AutomotorMarca::class, 'automotor_marca_id');
    }

    public function riesgo_automotor()
    {
        return $this->hasMany(RiesgoAutomotor::class, 'automotor_modelo_id', 'id');
    }
}
