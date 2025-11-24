<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockReactivo extends Model
{
    protected $table = "stock_reactivos_cancha";
    protected $fillable = [
        "fecha_hora",
        "usuario_id",
        "circuito",
        "producto_id",
        "stock"
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, "usuario_id");
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class, "producto_id");
    }
}
