<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = ['nombre', 'sabor', 'tipo', 'precio', 'activo'];

    public function inventarios()
    {
        return $this->hasMany(Inventario::class);
    }

    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
