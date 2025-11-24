<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesoOtraBal extends Model
{
    use HasFactory;

    protected $table = 'pesos_otras_bal';

    protected $fillable = [
        'fechai',
        'fechas',
        'bruto',
        'tara',
        'neto',
        'placa',
        'observacion',
        'producto',
        'conductor',
        'razon_social_id',
        'guia',
        'guiat',
        'origen',
        'destino',
        'balanza',
        'lote_id',
        'proceso_id',
        'programacion_id',
        'estado_id'
    ];

    public function proceso()
    {
        return $this->belongsTo(Proceso::class);
    }

    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }

    public function estado()
    {
        return $this->belongsTo(PsEstado::class, 'estado_id');
    }
}
