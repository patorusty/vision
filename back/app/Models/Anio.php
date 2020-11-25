<?php

namespace App;

use App\AutomotorAnio;
use Illuminate\Database\Eloquent\Model;

class Anio extends Model
{
    protected $guarded = [];

    public function automotor_anios(){
        return $this->hasMany(AutomotorAnio::class, 'anio_id', 'id');
    }
}
