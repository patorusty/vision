<?php

namespace App\Models;

use App\Models\AutomotorAnio;
use App\Models\AutomotorMarca;
use App\Models\AutomotorModelo;
use App\Models\AutomotorVersion;
use App\Models\Cobertura;
use App\Models\ImagenRA;
use App\Models\Poliza;
use Illuminate\Database\Eloquent\Model;

class RiesgoAutomotor extends Model
{
    protected $guarded = [];

    public function polizas()
    {
        return $this->belongsTo(Poliza::class, 'poliza_id');
    }

    public function anio()
    {
        return $this->belongsTo(AutomotorAnio::class, 'automotor_anio_id');
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
