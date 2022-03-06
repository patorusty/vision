<?php

namespace App\Models;

use App\Models\SiniestroAutomotor;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class NotaSiniestroAut extends Model
{
    protected $fillable = [
        'id',
        'siniestro_automotor_id',
        'user_id',
        'nota',
        'fecha',
        'created_at',
        'updated_at'
    ];

    protected $dates = [
        'fecha',
        'created_at',
        'updated_at'
    ];

    protected $table = 'nota_siniestro_auts';

    public function siniestro_automotor()
    {
        return $this->belongTo(SiniestroAutomotor::class, 'siniestro_automotor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
