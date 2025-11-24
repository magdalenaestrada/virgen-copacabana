<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reactivo extends Model
{
    protected $table = 'reactivos';

    protected $fillable = ['producto_id'];

    public function detalles(){
        return $this->hasMany(ReactivoDetalle::class);
        
    }
    public function productos(){
        return $this->belongsTo(Producto::class, 'producto_id');
    }

}
