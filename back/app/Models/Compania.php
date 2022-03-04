<?php

namespace App\Models;

use App\Models\Cobertura;
use App\Models\CodigoOrganizador;
use App\Models\CodigoProductor;
use App\Models\Localidades;
use Illuminate\Database\Eloquent\Model;

class Compania extends Model
{
    protected $fillable = [
        'nombre',
        'cuit',
        'direccion',
        'localidad_id',
        'telefono_1',
        'telefono_aux',
        'telefono_siniestros',
        'email_emision',
        'email_siniestros',
        'codigo_lr',
        'logo',
        'activo',
        'color',
        'created_at',
        'updated_at',
    ];

    public function localidades()
    {
        return $this->belongsTo(Localidades::class, 'localidad_id');
    }

    public function codigo_productor()
    {
        return $this->hasMany(CodigoProductor::class, 'compania_id', 'id');
    }

    public function codigo_organizador()
    {
        return $this->hasMany(CodigoOrganizador::class, 'compania_id', 'id');
    }

    public function coberturas()
    {
        return $this->hasMany(Cobertura::class, 'compania_id', 'id');
    }

    public function polizas()
    {
        return $this->hasMany(Poliza::class, 'compania_id', 'id');
    }

    public function polizas_vigentes()
    {
        return $this->hasMany(Poliza::class, 'compania_id', 'id')->where('estado_poliza_id', 3)->orWhere('estado_poliza_id', 4)->orWhere('estado_poliza_id', 7);
    }
}
