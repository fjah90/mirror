<?php

namespace App\Console\Commands;

use App\Models\ClienteContacto;
use App\Models\ProveedorContacto;
use App\Models\ContactoEmail;
use App\Models\ContactoTelefono;
use Illuminate\Console\Command;

class llenarTablasDeEmailsYTelefonos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oneTime:llenarTablasDeEmailsYTelefonos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Llena las tablas de contactos_emails y contactos_telefonos con los datos de contactos_clientes y contactos_proveedores';

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
        $contactos_cliente = ClienteContacto::all();
        $contactos_proveedor = ProveedorContacto::all();

        foreach($contactos_cliente as $contacto){
            if($contacto->email){
                ContactoEmail::create([
                    'contacto_id'=>$contacto->id, 
                    'contacto_type'=>'ProveedorContacto', 
                    'email'=>$contacto->email
                ]);
            }
            if($contacto->telefono){
                ContactoTelefono::create([
                    'contacto_id' => $contacto->id,
                    'contacto_type' => 'ProveedorContacto',
                    'telefono'=>$contacto->telefono, 
                    'telefono_extencion'=>$contacto->extencion_telefono, 
                    'telefono_tipo'=>$contacto->tipo_telefono
                ]);
            } 
            if($contacto->telefono2){
                ContactoTelefono::create([
                    'contacto_id' => $contacto->id,
                    'contacto_type' => 'ProveedorContacto',
                    'telefono'=>$contacto->telefono2, 
                    'telefono_extencion'=>$contacto->extencion_telefono2, 
                    'telefono_tipo'=>$contacto->tipo_telefono2
                ]);
            } 
        }

        foreach($contactos_proveedor as $contacto){
            if($contacto->email){
                ContactoEmail::create([
                    'contacto_id'=>$contacto->id, 
                    'contacto_type'=>'ProveedorContacto', 
                    'email'=>$contacto->email
                ]);
            }
            if($contacto->telefono){
                ContactoTelefono::create([
                    'contacto_id' => $contacto->id,
                    'contacto_type' => 'ProveedorContacto',
                    'telefono'=>$contacto->telefono, 
                    'telefono_extencion'=>$contacto->extencion_telefono, 
                    'telefono_tipo'=>$contacto->tipo_telefono
                ]);
            } 
            if($contacto->telefono2){
                ContactoTelefono::create([
                    'contacto_id' => $contacto->id,
                    'contacto_type' => 'ProveedorContacto',
                    'telefono'=>$contacto->telefono2, 
                    'telefono_extencion'=>$contacto->extencion_telefono2, 
                    'telefono_tipo'=>$contacto->tipo_telefono2
                ]);
            } 
        }

    }
}
