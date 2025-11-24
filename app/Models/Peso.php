<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peso extends Model
{
    protected $table = 'pesos';
    protected $fillable = ['Neto'];
    protected $primaryKey = 'NroSalida'; 


    public function estado()
    {
        return $this->hasOneThrough(
            PsEstado::class,        // The related model
            PsEstadoPeso::class,    // The intermediate table model
            'peso_id',              // Foreign key on ps_estados_pesos
            'id',                   // Foreign key on ps_estados
            'NroSalida',            // Local key on pesos
            'estado_id'             // Local key on ps_estados_pesos
        );
    }

    public function lote()
    {
        return $this->hasOneThrough(
            Lote::class,        // The related model
            PsLotePeso::class,    // The intermediate table model
            'peso_id',              // Foreign key on ps_estados_pesos
            'id',                   // Foreign key on ps_estados
            'NroSalida',            // Local key on pesos
            'lote_id'             // Local key on ps_estados_pesos
        );
    }

    public function proceso()
    {
        return $this->hasOneThrough(
            Proceso::class,        // The related model
            ProcesoPeso::class,    // The intermediate table model
            'peso_id',              // Foreign key on ps_estados_pesos
            'id',                   // Foreign key on ps_estados
            'NroSalida',            // Local key on pesos
            'proceso_id'             // Local key on ps_estados_pesos
        );
    }
}
