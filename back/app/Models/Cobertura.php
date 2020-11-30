<?php

namespace App\Models;

use App\Models\Companias;
use App\Models\RiesgoAutomotor;
use Illuminate\Database\Eloquent\Model;

class Coberturas extends Model
{
    protected $guarded = [];

    public function companias()
    {
        return $this->belongsTo(Compania::class, 'compania_id');
    }

    public function riesgo_automotor()
    {
        return $this->hasMany(RiesgoAutomotor::class, 'cobertura_id', 'id');
    }
}
