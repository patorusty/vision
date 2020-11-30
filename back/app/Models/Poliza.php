<?php

namespace App\Models;

use App\Models\CodigoProductor;
use App\Models\EstadoPoliza;
use App\Models\RiesgoAutomotor;
use App\Models\SiniestroAutomotor;
use App\Models\TipoRiesgo;
use App\Models\TipoVigencia;
use Illuminate\Database\Eloquent\Model;

class Poliza extends Model
{
    protected $guarded = [];

    public function companias()
    {
        return $this->belongsTo(Compania::class, 'compania_id');
    }

    public function clientes()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function codigo_productor()
    {
        return $this->belongsTo(CodigoProductor::class, 'codigo_productor_id');
    }

    public function tipoRiesgo()
    {
        return $this->belongsTo(TipoRiesgo::class, 'tipo_riesgo_id');
    }

    public function estado_polizas()
    {
        return $this->belongsTo(EstadoPoliza::class, 'estado_poliza_id');
    }

    public function tipo_vigencias()
    {
        return $this->belongsTo(TipoVigencia::class, 'tipo_vigencia_id');
    }

    public function forma_pagos()
    {
        return $this->belongsTo(TipoVigencia::class, 'forma_pago_id');
    }

    public function riesgo_automotor()
    {
        return $this->hasMany(RiesgoAutomotor::class, 'poliza_id', 'id');
    }

    public function endosos()
    {
        return $this->hasMany(Endosos::class, 'poliza_id', 'id');
    }

    public function siniestros()
    {
        return $this->hasMany(SiniestroAutomotor::class, 'poliza_id', 'id');
    }

}
