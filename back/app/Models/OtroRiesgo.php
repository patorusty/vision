<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtroRiesgo extends Model
{
    use HasFactory;

    protected $table = 'otros_riesgos';

    protected $fillable = [
        "poliza_id",
        "suma_10",
        "suma_9",
        "suma_8",
        "suma_7",
        "suma_6",
        "suma_5",
        "suma_4",
        "suma_3",
        "suma_2",
        "suma_1",
        "detalle_10",
        "detalle_9",
        "detalle_8",
        "detalle_7",
        "detalle_6",
        "detalle_5",
        "detalle_4",
        "detalle_3",
        "detalle_2",
        "detalle_1",
        "created_at",
        "updated_at",
    ];


    public function polizas()
    {
        return $this->belongsTo(Poliza::class, 'poliza_id');
    }

    public function notas_siniestros()
    {
        return $this->hasMany(NotaSiniestroAut::class, 'siniestro_automotor_id', 'id');
    }
}
