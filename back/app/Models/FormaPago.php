<?php

namespace App\Models;

use App\Models\Poliza;
use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    public function polizas()
    {
        return $this->hasMany(Poliza::class, 'forma_pago_id', 'id');
    }
}
