<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $fillable = ['producto_id', 'fecha', 'cantidad_inicial', 'cantidad_actual'];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}