<?php

namespace App\Models;

use App\Models\Compania;
use App\Models\RiesgoAutomotor;
use Illuminate\Database\Eloquent\Model;

class Cobertura extends Model
{
    protected $fillable = [
        'nombre',
        'compania_id',
        'antiguedad',
        'todo_riesgo',
        'franquicia',
        'activo',
        'ajuste',
        'detalle',
        'created_at',
        'updated_at',
    ];

    public function companias()
    {
        return $this->belongsTo(Compania::class, 'compania_id');
    }

    public function riesgo_automotor()
    {
        return $this->hasMany(RiesgoAutomotor::class, 'cobertura_id', 'id');
    }
}
