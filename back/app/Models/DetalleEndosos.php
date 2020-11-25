<?php

namespace App;

use App\TipoEndosos;
use App\TipoRiesgo;
use App\Endosos;
use Illuminate\Database\Eloquent\Model;

class DetalleEndosos extends Model
{
    protected $guarded = [];

    public function tipo_endoso() {
        return $this->belongsTo(TipoEndosos::class, 'tipo_endoso_id');
    }

    public function tipoRiesgo() {
        return $this->belongsTo(TipoRiesgo::class, 'tipo_riesgo_id');
    }

    public function endosos(){
        return $this->hasMany(Endosos::class, 'detalle_endoso_id', 'id');
    }
}
