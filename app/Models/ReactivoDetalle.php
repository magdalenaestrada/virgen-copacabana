<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReactivoDetalle extends Model
{
    protected $table = 'reactivos_detalles';

    protected $fillable =['reactivo_id', 'precio', 'limite'];


    public function reactivo(){
        return $this->belongsTo(Reactivo::class, 'reactivo_id');

    }
}
