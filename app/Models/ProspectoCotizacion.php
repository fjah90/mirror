<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\ProspectoCotizacionEntrada;

class ProspectoCotizacion extends Model
{
    protected $table = 'prospectos_cotizaciones';

    protected $fillable = ['prospecto_id', 'condicion_id', 'fecha', 'subtotal', 'iva',
        'total', 'observaciones', 'notas', 'archivo', 'entrega', 'lugar', 'moneda', 'facturar',
        'user_id', 'idioma', 'aceptada', 'notas2', 'numero', 'rfc', 'razon_social', 'calle',
        'nexterior', 'ninterior', 'colonia', 'cp', 'ciudad', 'estado', 'fletes','flete','flete_menor','costo_corte','costo_sobreproduccion','ubicacion', 'cliente_contacto_id',
        'direccion', 'dircalle', 'dirnexterior', 'dirninterior', 'dircolonia', 'dircp', 'dirciudad', 'direstado','descuentos','tipo_descuento',
        'contacto_email', 'contacto_telefono', 'contacto_nombre','instrucciones','enviar_a','documentacion','factibilidad','vendedor_id','cliente_id','nombre_proyecto'
    ];

    protected $casts = [
        'subtotal' => 'float',
        'iva'      => 'float',
        'total'    => 'float',
        'aceptada' => 'boolean',
        'facturar' => 'boolean',
    ];

    protected $appends = [
        'fecha_formated', 'direccion_facturacion', 'direccion_entrega', 'contacto_direccion_entrega'
    ];

    public function getFechaFormatedAttribute()
    {
        return Carbon::parse($this->fecha)->format('d/m/Y');
    }

    public function getDireccionFacturacionAttribute()
    {
        return $this->calle . " " . $this->nexterior . (($this->ninterior) ? " Int. " . $this->ninterior : "") . " "
        . $this->colonia . " " . $this->cp . " " . $this->ciudad . " " . $this->estado . " " . $this->pais;
    }

    public function getDireccionEntregaAttribute()
    {
        return $this->dircalle . " " . $this->dirnexterior . (($this->dirninterior) ? " Int. " . $this->dirninterior : "") . " "
        . $this->dircolonia . " " . $this->dircp . " " . $this->dirciudad . " " . $this->direstado . " " . $this->dirpais;
    }

    public function getContactoDireccionEntregaAttribute()
    {
        return $this->contacto_nombre . " " . $this->contacto_telefono . " " . $this->contacto_email . " " . $this->dircalle . " " . $this->dirnexterior . (($this->dirninterior) ? " Int. " . $this->dirninterior : "") . " "
            . $this->dircolonia . " " . $this->dircp . " " . $this->dirciudad . " " . $this->direstado . " " . $this->dirpais;
    }

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function prospecto()
    {
        return $this->belongsTo('App\Models\Prospecto', 'prospecto_id', 'id');
    }

    public function condiciones()
    {
        return $this->belongsTo('App\Models\CondicionCotizacion', 'condicion_id', 'id')
            ->withDefault(['nombre' => '']);
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function contacto()
    {
        return $this->belongsTo('App\Models\ClienteContacto', 'cliente_contacto_id', 'id')
            ->withDefault(['id' => 0, 'nombre' => 'Por Definir', 'email' => '']);
    }

    public function entradas()
    {
        return $this->hasMany('App\Models\ProspectoCotizacionEntrada', 'cotizacion_id', 'id')
            ->orderBy('orden', 'asc');
    }

    public function entradas2()
    {
        $entradas = ProspectoCotizacionEntrada::leftjoin('productos','producto_id','=','productos.id')
        ->leftjoin('proveedores','productos.proveedor_id','=','proveedores.id')
        ->select('proveedores.empresa', DB::raw('sum(prospectos_cotizaciones_entradas.importe) as total_importe') )->groupBy('proveedores.empresa')
        ->where('prospectos_cotizaciones_entradas.cotizacion_id',$this->id)->get();  

        return $entradas;

    }

    public function entradasProveedor()
    {
        return $this->hasMany('App\Models\ProspectoCotizacionEntrada', 'cotizacion_id', 'id')
            ->orderBy('orden', 'asc');
    }

    public function cuentaCobrar()
    {
        return $this->hasOne('App\Models\CuentaCobrar', 'cotizacion_id', 'id');
    }

    public function proyecto_aprobado()
    {
        return $this->hasOne('App\Models\ProyectoAprobado', 'cotizacion_id', 'id');
    }

    public function cuenta_cobrar()
    {
        return $this->hasOne('App\Models\CuentaCobrar', 'cotizacion_id', 'id');
    }

    public function cliente()
    {
        return $this->hasOne('App\Models\Cliente','id', 'cliente_id');
    }

}
