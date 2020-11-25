<?php

namespace App;

use App\TipoEndosos;
use App\Endosos;
use App\DetalleEndosos;
use Illuminate\Database\Eloquent\Model;

class TipoEndosos extends Model
{
    public function endosos(){
        return $this->hasMany(Endosos::class, 'tipo_endoso_id', 'id');
    }

    public function detalleEndosos(){
        return $this->hasMany(DetalleEndosos::class, 'tipo_endoso_id', 'id');
    }
}
