<?php

namespace App;
use App\Polizas;
use Illuminate\Database\Eloquent\Model;

class EstadoPoliza extends Model
{
    protected $guarded = [];

    public function polizas()
    {
    return $this->hasMany(Polizas::class, 'estado_poliza_id', 'id');
    }
}
