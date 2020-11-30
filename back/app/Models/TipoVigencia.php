<?php

namespace App\Models;

use App\Models\Poliza;
use Illuminate\Database\Eloquent\Model;

class TipoVigencia extends Model
{
    protected $guarded = [];

    public function polizas()
    {
        return $this->hasMany(Poliza::class, 'tipo_vigencia_id', 'id');
    }
}
