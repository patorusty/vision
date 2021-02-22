<?php

namespace App\Models;

use App\Models\CodigoProductor;
use Illuminate\Database\Eloquent\Model;

class CodigoOrganizador extends Model
{
    protected $fillable = [
        'codigo_organizador',
        'organizador_id',
        'compania_id',
        'activo',
        'created_at',
        'updated_at',
    ];



    public function organizadores()
    {
        return $this->belongsTo(Organizador::class, 'organizador_id');
    }
    public function companias()
    {
        return $this->belongsTo(Companias::class, 'compania_id');
    }

    public function codigo_productor()
    {
        return $this->hasMany(CodigoProductor::class, 'codigo_organizador_id', 'id');
    }
}
