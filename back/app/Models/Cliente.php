<?php

namespace App\Models;

use App\Models\Productor;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'apellido',
        'barrio_cerrado',
        'celular',
        'condicion_fiscal',
        'created_at',
        'cuit',
        'direccion',
        'direccion_depto',
        'direccion_nro',
        'direccion_piso',
        'email',
        'email_alt',
        'img_registro',
        'localidad_id',
        'lote',
        'nacimiento',
        'nombre',
        'nro_dni',
        'observaciones_1',
        'observaciones_2',
        'productor_id',
        'razon_social',
        'registro',
        'sexo',
        'telefono_1',
        'telefono_2',
        'tipo_doc',
        'tipo_persona',
        'updated_at',
        'vencimiento_registro',
    ];

    protected $dates = [
        'nacimiento'
    ];

    public function productores()
    {
        return $this->belongsTo(Productor::class, 'productor_id');
    }
}
