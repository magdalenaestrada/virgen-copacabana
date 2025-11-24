<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramacionDetalle extends Model
{
    use HasFactory;
    protected $table = 'programacion_detalles';
    protected $fillable = ['programacion_id', 'peso_id'];

    public function programacion()
    {
        return $this->belongsTo(PlProgramacion::class, 'programacion_id');
    }

    public function peso()
    {
        return $this->belongsTo(Peso::class, 'peso_id');
    }
}
