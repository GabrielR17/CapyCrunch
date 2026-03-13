<?php
namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Inventario;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();

        // Dinero recaudado hoy (solo ventas pagadas)
        $recaudadoHoy = Venta::where('fecha', $hoy)
            ->where('estado_pago', 'pagada')
            ->sum('total');

        // Ventas pendientes de cobro
        $pendientes = Venta::where('estado_pago', 'pendiente')->get();
        $totalPendiente = $pendientes->sum('total');

        // Stock total disponible hoy
        $stockHoy = Inventario::where('fecha', $hoy)->sum('cantidad_actual');

        // Producto más vendido hoy
        $productoTop = \App\Models\DetalleVenta::whereHas('venta', function($q) use ($hoy) {
                $q->where('fecha', $hoy);
            })
            ->selectRaw('producto_id, SUM(cantidad) as total_vendido')
            ->groupBy('producto_id')
            ->orderByDesc('total_vendido')
            ->with('producto')
            ->first();

        return view('dashboard', compact(
            'recaudadoHoy', 'pendientes', 'totalPendiente', 'stockHoy', 'productoTop'
        ));
    }
}
