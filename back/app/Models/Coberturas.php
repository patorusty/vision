<?php

namespace App;

use App\Companias;
use App\RiesgoAutomotor;
use Illuminate\Database\Eloquent\Model;

class Coberturas extends Model
{
    protected $guarded = [];


    public function companias() {
        return $this->belongsTo(Companias::class, 'compania_id');
    }

    public function riesgo_automotor() {
        return $this->hasMany(RiesgoAutomotor::class, 'cobertura_id', 'id');
    }
}
