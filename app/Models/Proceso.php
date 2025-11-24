<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proceso extends Model
{
    protected $table = 'procesos';
    protected $fillable = ['id', 'lote_id', 'peso_total', 'circuito', 'codigo'];


    public function pesos()
    {
        return $this->hasManyThrough(
            Peso::class,
            ProcesoPeso::class,
            'proceso_id',
            'NroSalida',
            'id',
            'peso_id'
        );
    }


    public function lote()
    {
        return $this->belongsTo(Lote::class, 'lote_id');
    }

    public function programacion()
    {
        return $this->hasOne(PlProgramacion::class, 'proceso_id');
    }


    public function consumosreactivos()
    {
        return $this->hasMany(ConsumoReactivo::class);
    }

    public function devolucionesReactivos()
    {
        return $this->hasMany(DevolucionReactivo::class);
    }

    public function liquidacion()
    {
        return $this->hasOne(PlLiquidacion::class, 'proceso_id');
    }

    public function pesosOtrasBal()
    {
        return $this->hasMany(PesoOtraBal::class, 'proceso_id');
    }
}
