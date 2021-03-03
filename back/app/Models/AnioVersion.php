<?php

namespace App\Models;

use App\Models\AutomotorVersion;
use App\Models\RiesgoAutomotor;
use App\Models\Anio;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AnioVersion extends Pivot
{
  protected $guarded = [];

  protected $table = "anio_version";

  // public function versiones()
  // {
  //   return $this->belongsTo(AutomotorVersion::class, 'automotor_version_id');
  // }
  // public function anios()
  // {
  //   return $this->belongsTo(Anio::class, 'anio_id');
  // }
  // public function riesgo_automotor()
  // {
  //   return $this->hasMany(RiesgoAutomotor::class, 'automotor_anio_id', 'id');
  // }
}
