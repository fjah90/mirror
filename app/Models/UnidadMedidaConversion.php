<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class UnidadMedidaConversion extends Model
{
    protected $table = 'vista_unidades_medida_conversiones';

    protected $fillable = ['unidad_medida_id','unidad_conversion_id',
      'unidad_conversion_simbolo','unidad_conversion_nombre','factor_conversion'
    ];

    public function update(array $attributes = [], array $options = [])
    {
        $this->setTable('unidades_medida_conversiones');
        parent::update($attributes);
    }

    public function save(array $options = []){
      $this->setTable('unidades_medida_conversiones');
      parent::save($options);
    }

    public function create(array $options = []){
      $this->setTable('unidades_medida_conversiones');
      parent::create($options);
    }

}
