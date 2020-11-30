<?php

namespace App\Models;

use App\Models\RiesgoAutomotor;
use Illuminate\Database\Eloquent\Model;

class ImagenRA extends Model
{
    protected $table = 'imagen_r_as';
    protected $guarded = [];

    public function riesgo_automotor()
    {
        return $this->belongsToMany(RiesgoAutomotor::class);
    }
}
