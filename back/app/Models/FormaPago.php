<?php

namespace App;

use App\Polizas;
use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    public function polizas()
    {
    return $this->hasMany(Polizas::class, 'forma_pago_id', 'id');
    }
}
