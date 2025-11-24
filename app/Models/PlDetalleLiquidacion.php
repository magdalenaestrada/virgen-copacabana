<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlDetalleLiquidacion extends Model
{
    protected $table = 'pl_detalle_liquidaciones';
    protected $fillable = ['precio_unitario_proceso', 'cantidad_procesada_tn', 'precio_unitario_laboratorio',
    'cantidad_muestras','precio_unitario_balanza', 'cantidad_pesajes', 'liquidacion_id' , 'precio_prueba_metalurgica',
     'cantidad_pruebas_metalurgicas', 'precio_unitario_comida', 'cantidad_comidas','precio_descoche',  // 👈 nuevo
    'cantidad_descoche', ];

}
