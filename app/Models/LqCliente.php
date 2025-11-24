<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LqCliente extends Model
{
    use HasFactory;

    protected $table = 'lq_clientes';
    protected $fillable = ['codigo','documento', 'nombre', 'creador_id'];

    public function lotes(){
        return $this->hasMany(Lote::class, "lq_cliente_id");
    }
}
