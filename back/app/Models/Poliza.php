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
    protected $fillable = [
        'cliente_id',
        'compania_id',
        'codigo_productor_id',
        'tipo_riesgo_id',
        'numero_solicitud',
        'numero',
        'estado_poliza_id',
        'renueva_numero',
        'tipo_vigencia_id',
        'vigencia_dias',
        'vigencia_desde',
        'vigencia_hasta',
        'fecha_solicitud',
        'fecha_emision',
        'fecha_recepcion',
        'fecha_entrega_correo',
        'fecha_entrega_original',
        'fecha_entrega_email',
        'premio',
        'prima',
        'plan_pago',
        'cantidad_cuotas',
        'forma_pago_id',
        'detalle_medio_pago',
        'comision',
        'descuento',
        'archivada',
        'created_at',
        'updated_at',
    ];

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
