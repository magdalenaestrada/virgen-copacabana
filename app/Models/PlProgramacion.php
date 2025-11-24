<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlProgramacion extends Model
{
    protected $table = 'pl_programaciones';

    protected $fillable = ['id', 'fecha_inicio', 'fecha_fin', 'proceso_id'];

    public function proceso(){
        return $this->belongsTo(Proceso::class, 'proceso_id');
    }

}
