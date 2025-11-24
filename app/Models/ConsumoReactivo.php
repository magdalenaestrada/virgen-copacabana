<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsumoReactivo extends Model
{
    protected $table = 'consumo_reactivos';
    protected $fillable = ['cantidad', 'proceso_id', 'reactivo_id', 'fecha'];

    public function reactivo(){
       return $this->belongsTo(Reactivo::class, 'reactivo_id');
    }
}
