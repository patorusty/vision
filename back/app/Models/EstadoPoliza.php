<?php

namespace App;

use App\Models\Poliza;
use Illuminate\Database\Eloquent\Model;

class EstadoPoliza extends Model
{
    protected $guarded = [];

    public function polizas()
    {
        return $this->hasMany(Poliza::class, 'estado_poliza_id', 'id');
    }
}
