<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    protected $table = 'lotes';
    protected $fillable = ['codigo', 'nombre', 'activo', 'lq_cliente_id'];


    public function pesos()
    {
        return $this->hasManyThrough(
            Peso::class,        // The related model
            PsLotePeso::class,    // The intermediate table model
            'lote_id',              // Foreign key on ps_estados_pesos
            'NroSalida',                   // Foreign key on ps_estados
            'id',            // Local key on pesos
            'peso_id'             // Local key on ps_estados_pesos
        );
    }


    public function procesos()
    {
        return $this->hasMany(Proceso::class, 'lote_id');
    }

    public function cliente()
    {
        return $this->belongsTo(LqCliente::class, 'lq_cliente_id');
    }
}
