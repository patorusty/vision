<?php

namespace App\Models;

use App\Models\DetalleEndoso;
use App\Models\Poliza;
use Illuminate\Database\Eloquent\Model;

class TipoRiesgo extends Model
{
    public function ramo()
    {
        return $this->belongsTo(Ramo::class, 'ramo_id');
    }

    public function polizas()
    {
        return $this->hasMany(Poliza::class, 'tipo_riesgo_id', 'id');
    }

    public function detalleEndosos()
    {
        return $this->hasMany(DetalleEndoso::class, 'tipo_riesgo_id', 'id');
    }
}
