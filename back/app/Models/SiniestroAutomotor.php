<?php

namespace App;

use App\Polizas;
use App\NotaSiniestroAut;
use Illuminate\Database\Eloquent\Model;

class SiniestroAutomotor extends Model
{
    protected $guarded = [];
    
    public function polizas() {
        return $this->belongsTo(Polizas::class, 'poliza_id');
    }

    public function notas_siniestros(){
        return $this->hasMany(NotaSiniestroAut::class, 'siniestro_automotor_id', 'id');
    }
}
