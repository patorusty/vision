<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TipoRiesgo;
use App\Models\Poliza;

class OtroRiesgo extends Model
{
    use HasFactory;

    protected $table = 'otros_riesgos';

    protected $fillable = [
        'poliza_id',
        'tipo_riesgo_id',
        'direccion',
        'nro',
        'piso',
        'depto',
        'barrio',
        'lote',
        'localidad_id',
        'detalle',
        'actividad',
        'suma_asegurada',
        'tipo_embarcacion',
        'matricula',
        'tipo',
        'cobertura'
    ];


    public function polizas()
    {
        return $this->belongsTo(Poliza::class, 'poliza_id');
    }
}
