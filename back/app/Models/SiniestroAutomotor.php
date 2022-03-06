<?php

namespace App\Models;

use App\Models\NotaSiniestroAut;
use App\Models\Poliza;
use Illuminate\Database\Eloquent\Model;

class SiniestroAutomotor extends Model
{
    protected $fillable = [
        "poliza_id",
        "numero_siniestro",
        "fecha_denuncia",
        "fecha_siniestro",
        "fecha_completo",
        "tipo_reclamo",
        "estado_siniestro",
        "compania_tercero",
        "detalle",
        "created_at",
        "updated_at",
    ];

    public function poliza()
    {
        return $this->belongsTo(Poliza::class, 'poliza_id');
    }

    public function notas()
    {
        return $this->hasMany(NotaSiniestroAut::class, 'siniestro_automotor_id', 'id');
    }
}
