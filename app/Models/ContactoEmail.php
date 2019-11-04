<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactoEmail extends Model
{
    protected $table = 'contactos_emails';

    protected $fillable = ['contacto_id','contacto_type','email','tipo'];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function contacto(){
      return $this->morphTo();
    }

}
