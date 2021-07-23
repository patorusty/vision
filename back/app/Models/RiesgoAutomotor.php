<?php

namespace App\Models;

use App\Models\Anio;
use App\Models\AutomotorMarca;
use App\Models\AutomotorModelo;
use App\Models\AutomotorVersion;
use App\Models\Cobertura;
use App\Models\ImagenRA;
use App\Models\Poliza;
use Illuminate\Database\Eloquent\Model;

class RiesgoAutomotor extends Model
{
    protected $fillable = [
        'poliza_id',
        'automotor_tipo',
        'automotor_anio_id',
        'automotor_marca_id',
        'automotor_modelo_id',
        'automotor_version_id',
        'tipo_patente',
        'patente',
        'nro_motor',
        'nro_chasis',
        'uso',
        'tipo_carroceria',
        'combustible',
        'okm',
        'estado_general',
        'color',
        'equipo_rastreo',
        'gnc',
        'gnc_nro_oblea',
        'gnc_vencimiento_oblea',
        'gnc_nro_regulador',
        'gnc_marca_regulador',
        'gnc_nro_cilindro',
        'gnc_marca_cilindro',
        'cubiertas_medida',
        'cubiertas_marca',
        'accesorio_01',
        'valor_accesorio_01',
        'accesorio_02',
        'valor_accesorio_02',
        'acreedor_prendario',
        'acreedor_rs',
        'acreedor_cuit',
        'observaciones',
        'cobertura_id',
        'franquicia',
        'ajuste',
        'valor_vehiculo',
        'valor_gnc',
        'valor_accesorios',
        'valor_total',
        'vigencia_desde',
        'vigencia_hasta',
        'created_at',
        'updated_at'
    ];

    public function polizas()
    {
        return $this->belongsTo(Poliza::class, 'poliza_id');
    }

    public function anio()
    {
        return $this->belongsTo(Anio::class, 'automotor_anio_id');
    }

    public function marca()
    {
        return $this->belongsTo(AutomotorMarca::class, 'automotor_marca_id');
    }

    public function modelo()
    {
        return $this->belongsTo(AutomotorModelo::class, 'automotor_modelo_id');
    }

    public function version()
    {
        return $this->belongsTo(AutomotorVersion::class, 'automotor_version_id');
    }

    public function cobertura()
    {
        return $this->belongsTo(Cobertura::class, 'cobertura_id');
    }

    public function imagenes()
    {
        return $this->belongsToMany(ImagenRA::class);
    }
}
