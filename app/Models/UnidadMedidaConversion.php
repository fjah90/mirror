<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class UnidadMedidaConversion extends Model
{
    protected $table = 'vista_unidades_medida_conversiones';

    protected $fillable = [
      'unidad_medida_id','unidad_conversion_id','factor_conversion'
    ];

    // public function update(array $attributes = [], array $options = []){
    //   $this->setTable('unidades_medida_conversiones');
    //   parent::update($attributes);
    // }
    //
    // public function save(array $options = []){
    //   $this->setTable('unidades_medida_conversiones');
    //   parent::save($options);
    // }

    // public static function create($fillables){
    //   $conversion = new static;
    //   $conversion->fill($fillables);
    //   $conversion->save();
    // }

}
