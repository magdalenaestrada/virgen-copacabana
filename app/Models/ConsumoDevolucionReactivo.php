<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumoDevolucionReactivo extends Model
{
    protected $table = "";
    protected $fillable = [
        "proceso_id",
        "tipo",
        "cantidad",
        "reactivo_id",
    ];
    public function proceso()
    {
        return $this->belongsTo(Proceso::class, "proceso_id");
    }
    public function reactivo()
    {
        return $this->belongsTo(Producto::class, "reactivo_id");
    }
}
