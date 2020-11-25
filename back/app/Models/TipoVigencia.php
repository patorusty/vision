<?php

namespace App;
use App\Polizas;
use Illuminate\Database\Eloquent\Model;

class TipoVigencia extends Model
{
    protected $guarded = [];

    public function polizas()
    {
    return $this->hasMany(Polizas::class, 'tipo_vigencia_id', 'id');
    }
}
