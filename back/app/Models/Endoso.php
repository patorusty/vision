<?php

namespace App;

use App\Models\DetalleEndoso;
use App\Models\Poliza;
use App\Models\TipoEndoso;
use Illuminate\Database\Eloquent\Model;

class Endoso extends Model
{

    protected $guarded = [];

    public function polizas()
    {
        return $this->belongsTo(Poliza::class, 'poliza_id');
    }

    public function tipo_endosos()
    {
        return $this->belongsTo(TipoEndoso::class, 'tipo_endoso_id');
    }

    public function detalle_endosos()
    {
        return $this->belongsTo(DetalleEndoso::class, 'detalle_endoso_id');
    }
}
