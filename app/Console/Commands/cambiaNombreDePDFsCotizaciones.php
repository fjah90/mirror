<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Prospecto;
use Storage;

class cambiaNombreDePDFsCotizaciones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oneTime:cambiaNombreDePDFsCotizaciones';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'cambia el nombre de los pdfs de cotizacion a este formato: Cotización Numero Intercorp Proyecto';

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
     * @return mixed
     */
    public function handle()
    {
      $prospectos = Prospecto::with('cotizaciones')->get();

      foreach ($prospectos as $prospecto) {
        foreach ($prospecto->cotizaciones as $cotizacion) {
          if(is_null($cotizacion->archivo)) continue;

          $new_name = "cotizaciones/".$cotizacion->id."/Cotizacion ".$cotizacion->id." Intercorp ".$prospecto->nombre.".pdf";
          Storage::disk('public')->copy($cotizacion->archivo, $new_name);
          Storage::disk('public')->delete($cotizacion->archivo);
          $cotizacion->update(['archivo'=>$new_name]);
        }
      }

      echo 'Hecho';
    }
}
