<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Localidades extends Model
{
    public function companias()
    {
        return $this->hasMany(Companias::class, 'localidad_id', 'id');
    }
}
