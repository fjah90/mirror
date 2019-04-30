<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Categoria;

class ordenarDescripcionesDeCategorias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oneTime:ordenarDescripcionesDeCategorias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ordena las decripciones de las categorias de acuerdo a su id (ascendente)';

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
      $categorias = Categoria::with('descripciones')->get();

      foreach ($categorias as $categoria) {
        $i=1;
        foreach ($categoria->descripciones as $descripcion) {
          $descripcion->update(['ordenamiento'=>$i++]);
        }
      }

      echo "Descripciones ordenadas".PHP_EOL;
    }
}
