<?php

namespace App\Models;

use App\Models\Endoso;
use App\Models\TipoEndoso;
use App\Models\TipoRiesgo;
use Illuminate\Database\Eloquent\Model;

class DetalleEndoso extends Model
{
    protected $guarded = [];

    public function tipo_endoso()
    {
        return $this->belongsTo(TipoEndoso::class, 'tipo_endoso_id');
    }

    public function tipoRiesgo()
    {
        return $this->belongsTo(TipoRiesgo::class, 'tipo_riesgo_id');
    }

    public function endosos()
    {
        return $this->hasMany(Endoso::class, 'detalle_endoso_id', 'id');
    }
}
