<?php

namespace App\Models;

use App\Models\DetalleEndoso;
use App\Models\Poliza;
use App\Models\TipoEndoso;
use Illuminate\Database\Eloquent\Model;

class Endoso extends Model
{

    protected $fillable = [
        "poliza_id",
        "tipo_endoso_id",
        "detalle_endoso_id",
        "fecha_solicitud",
        "fecha_emision",
        "detalle",
        "numero_endoso",
        "completo",
        "created_at",
        "updated_at"
    ];

    public function polizas()
    {
        return $this->belongsTo(Poliza::class, 'poliza_id');
    }

    public function tipo_endoso()
    {
        return $this->belongsTo(TipoEndoso::class, 'tipo_endoso_id');
    }

    public function detalle_endoso()
    {
        return $this->belongsTo(DetalleEndoso::class, 'detalle_endoso_id');
    }
}
