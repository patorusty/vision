<?php

namespace App;

use App\Productores;
use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    protected $guarded = [];

    public function productores() {
       return $this->belongsTo(Productores::class, 'productor_id');
    }
}
