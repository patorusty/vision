<?php

namespace App;

use App\Polizas;
use App\AutomotorAnio;
use App\AutomotorMarca;
use App\AutomotorModelo;
use App\AutomotorVersion;
use App\Coberturas;
use App\ImagenRA;

use Illuminate\Database\Eloquent\Model;

class RiesgoAutomotor extends Model
{
   protected $guarded = [];

   public function polizas()
   {
      return $this->belongsTo(Polizas::class, 'poliza_id');
   }

   public function automotor_anio()
   {
      return $this->belongsTo(AutomotorAnio::class, 'automotor_anio_id');
   }

   public function automotor_marca()
   {
      return $this->belongsTo(AutomotorMarca::class, 'automotor_marca_id');
   }

   public function automotor_modelo()
   {
      return $this->belongsTo(AutomotorModelo::class, 'automotor_modelo_id');
   }

   public function automotor_version()
   {
      return $this->belongsTo(AutomotorVersion::class, 'automotor_version_id');
   }

   public function cobertura()
   {
      return $this->belongsTo(Coberturas::class, 'cobertura_id');
   }

   public function imagenes() 
   {
      return $this->belongsToMany(ImagenRA::class);
   }

}
