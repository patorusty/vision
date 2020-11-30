<?php

namespace App\Models;

use App\Models\SiniestroAutomotor;
use Illuminate\Database\Eloquent\Model;

class NotaSiniestroAut extends Model
{
    protected $guarded = [];

    public function siniestro_automotor()
    {
        return $this->belongTo(SiniestroAutomotor::class, 'siniestro_automotor_id');
    }
}
