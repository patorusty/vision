<?php

namespace App\Models;

use App\Models\DetalleEndoso;
use App\Models\Endoso;
use Illuminate\Database\Eloquent\Model;

class TipoEndoso extends Model
{
    public function endosos()
    {
        return $this->belongsTo(Endoso::class, 'tipo_endoso_id', 'id');
    }

    public function detalleEndosos()
    {
        return $this->hasMany(DetalleEndoso::class, 'tipo_endoso_id', 'id');
    }
}
