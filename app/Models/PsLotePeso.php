<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PsLotePeso extends Model
{
    protected $table = 'ps_lotes_pesos';

    protected $fillable = ['peso_id', 'lote_id'];

    public function lote()
    {
        return $this->belongsTo(Lote::class, 'lote_id', 'id');
    }
}
