<?php

namespace App\Models;

use App\Models\CodigoOrganizador;
use App\Models\Polizas;
use Illuminate\Database\Eloquent\Model;

class CodigoProductor extends Model
{
    protected $fillable = [
        'codigo_productor',
        'codigo_organizador_id',
        'productor_id',
        'compania_id',
        'activo',
        'created_at',
        'updated_at',
    ];

    public function productores()
    {
        return $this->belongsTo(Productor::class, 'productor_id');
    }

    public function companias()
    {
        return $this->belongsTo(Compania::class, 'compania_id');
    }

    public function codigo_organizador()
    {
        return $this->belongsTo(CodigoOrganizador::class, 'codigo_organizador_id');
    }

    public function polizas()
    {
        return $this->hasMany(Poliza::class, 'codigo_productor_id', 'id');
    }
}
