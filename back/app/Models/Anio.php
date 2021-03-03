<?php

namespace App\Models;

use App\Models\AutomotorVersion;
use Illuminate\Database\Eloquent\Model;

class Anio extends Model
{
    protected $guarded = [];

    public function versiones()
    {
        return $this->belongsToMany(AutomotorVersion::class, 'anio_version', 'anio_id', 'automotor_version_id');
    }
}
