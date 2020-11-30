<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Localidad extends Model
{
    public function companias()
    {
        return $this->hasMany(Compania::class, 'localidad_id', 'id');
    }

    protected $table = 'localidades';
}
