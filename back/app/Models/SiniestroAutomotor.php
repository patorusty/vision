<?php

namespace App\Models;

use App\Models\NotaSiniestroAut;
use App\Models\Poliza;
use Illuminate\Database\Eloquent\Model;

class SiniestroAutomotor extends Model
{
    protected $guarded = [];

    public function polizas()
    {
        return $this->belongsTo(Poliza::class, 'poliza_id');
    }

    public function notas_siniestros()
    {
        return $this->hasMany(NotaSiniestroAut::class, 'siniestro_automotor_id', 'id');
    }
}
