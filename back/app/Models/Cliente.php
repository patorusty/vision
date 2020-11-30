<?php

namespace App\Models;

use App\Models\Productor;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $guarded = [];

    public function productores()
    {
        return $this->belongsTo(Productor::class, 'productor_id');
    }
}
