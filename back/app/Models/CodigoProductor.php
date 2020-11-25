<?php

namespace App\Models;

use App\Models\CodigoOrganizador;
use App\Models\Companias;
use App\Models\Polizas;
use App\Models\Productores;
use Illuminate\Database\Eloquent\Model;

class CodigoProductor extends Model
{
    protected $guarded = [];

    public function productores()
    {
        return $this->belongsTo(Productores::class, 'productor_id');
    }

    public function companias()
    {
        return $this->belongsTo(Companias::class, 'compania_id');
    }

    public function codigo_organizador()
    {
        return $this->belongsTo(CodigoOrganizador::class, 'codigo_organizador_id');
    }

    public function polizas()
    {
        return $this->hasMany(Polizas::class, 'codigo_productor_id', 'id');
    }
}
