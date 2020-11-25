<?php

namespace App\Models;

use App\Models\Coberturas;
use App\Models\CodigoOrganizador;
use App\Models\CodigoProductor;
use App\Models\Localidades;
use Illuminate\Database\Eloquent\Model;

class Compania extends Model
{
    protected $guarded = [];

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
        return $this->hasMany(Coberturas::class, 'compania_id', 'id');
    }

    public function polizas()
    {
        return $this->hasMany(Polizas::class, 'compania_id', 'id');
    }
}
