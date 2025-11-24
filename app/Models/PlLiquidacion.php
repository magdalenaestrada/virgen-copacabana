<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlLiquidacion extends Model
{
    protected $table ='pl_liquidaciones';

    protected $fillable = ['suma_proceso', 'suma_exceso_reactivos', 'suma_balanza', 'suma_prueba_metalurgica',  'suma_descoche', 'gastos_adicionales', 'suma_laboratorio', 'suma_comedor', 'subtotal', 'igv', 'total', 'fecha', 'proceso_id', 'cliente_id', 'creador_id'];

    public function proceso(){
        return $this->belongsTo(Proceso::class);
    }


    public function cliente(){
        return $this->belongsTo(LqCliente::class, 'cliente_id');
    }


    public function detalle(){
        return $this->hasOne(PlDetalleLiquidacion::class, 'liquidacion_id');
    }


    public function detalles_reactivos(){
        return $this->hasMany(LiquidacionReactivoDetalle::class, 'liquidacion_id');
    }


    public function creador(){
        return $this->belongsTo(User::class, 'creador_id');
    }

                              

}
