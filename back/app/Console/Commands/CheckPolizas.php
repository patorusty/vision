<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Poliza;
use Carbon\Carbon;

class CheckPolizas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:checkpolizas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checkeo diario de polizas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $hoy = Carbon::now();

        $polizas = Poliza::all();
        foreach ($polizas as $poliza) {
            $p = Poliza::findOrFail($poliza["id"]);
            // var_dump($p->renueva_numero);
            if ($poliza["estado_poliza_id"] == 3) {
                // var_dump($poliza["renueva_numero"]);
                $poliza["renueva_numero"] = 'hola';
                $poliza->save();
                // $poliza->renueva_numero = 'jola';
                // switch ($poliza) {
                //     case $hoy->isAfter($poliza['vigencia_desde']) && $hoy->isBefore($poliza['vigencia_hasta']->subMonth()) && !$poliza['renueva_numero']:
                //         $poliza["estado_id"] = 2;
                //         //VIGENTE
                //         break;
                //     case $hoy->isAfter($poliza['vigencia_hasta']->subMonth()) && $hoy->isBefore($poliza['vigencia_hasta']) && !$poliza['renueva_numero']:
                //         $poliza["estado_id"] = 3;
                //         //VIGENTE A RENOVAR
                //         break;
                //     case $hoy->isAfter($poliza['vigencia_desde']) && $hoy->isBefore($poliza['vigencia_hasta']) && $poliza['renueva_numero']:
                //         $poliza["estado_id"] = 6;
                //         //VIGENTE RENOVADA
                //         break;
                //     case $hoy->isAfter($poliza['vigencia_hasta']) && $poliza['renueva_numero']:
                //         $poliza["estado_id"] = 4;
                //         //CUMPLIDA RENOVADA
                //         break;
                //     case $hoy->isAfter($poliza['vigencia_hasta']):
                //         $poliza["estado_id"] = 5;
                //         //CUMPLIDA
                //         break;
                //     case $hoy->isBefore($poliza['vigencia_desde']):
                //         $poliza["estado_id"] = 1;
                //         // PENDIENTE
                //         break;
                //     default:
                //         # code...
                //         break;
                // }
            }
        }
    }
}
