<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiquidacionReactivoDetalle extends Model
{
    protected $table = 'liquidaciones_reactivos_detalles';
    protected $fillable = ['liquidacion_id', 'reactivo_detalle_id'];

    public function detalle_r(){
        return $this->belongsTo(ReactivoDetalle::class, 'reactivo_detalle_id');
    }


}
