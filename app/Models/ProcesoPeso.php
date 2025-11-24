<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcesoPeso extends Model
{
    protected $table = 'procesos_pesos';

    protected $fillable = ['proceso_id', 'peso_id'];

    public function peso()
    {
        return $this->belongsTo(Peso::class, 'peso_id');
    }
    public function proceso()
    {
        return $this->belongsTo(Proceso::class, 'proceso_id');
    }
}
