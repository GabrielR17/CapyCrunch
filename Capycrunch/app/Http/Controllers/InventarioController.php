<?php
namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InventarioController extends Controller
{
    public function index()
    {
        $productos  = Producto::where('activo', true)->get();
        $hoy        = Carbon::today();
        $inventario = Inventario::where('fecha', $hoy)
            ->with('producto')->get()
            ->keyBy('producto_id');
        return view('inventario.index', compact('productos', 'inventario', 'hoy'));
    }

    public function store(Request $request)
    {
        $hoy = Carbon::today();
        foreach ($request->cantidades as $productoId => $cantidad) {
            Inventario::updateOrCreate(
                ['producto_id' => $productoId, 'fecha' => $hoy],
                ['cantidad_inicial' => $cantidad, 'cantidad_actual' => $cantidad]
            );
        }
        return redirect()->route('inventario.index')
            ->with('success', 'Inventario guardado correctamente');
    }
}