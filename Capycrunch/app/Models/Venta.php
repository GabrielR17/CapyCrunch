<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'cliente_id', 'fecha', 'total',
        'metodo_pago', 'estado_pago', 'fecha_pago', 'nota'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}