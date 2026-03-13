<?php
namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use Carbon\Carbon;

class EstadisticaController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();

        // Totales generales (solo pagadas)
        $totalHoy     = Venta::where('fecha', $hoy)->where('estado_pago','pagada')->sum('total');
        $totalMes     = Venta::whereMonth('fecha', $hoy->month)->where('estado_pago','pagada')->sum('total');
        $totalPendiente = Venta::where('estado_pago','pendiente')->sum('total');

        // Por método de pago (hoy)
        $porMetodo = Venta::where('fecha', $hoy)
            ->where('estado_pago', 'pagada')
            ->selectRaw('metodo_pago, SUM(total) as total')
            ->groupBy('metodo_pago')
            ->pluck('total', 'metodo_pago');

        // Productos más vendidos (hoy)
        $porProducto = DetalleVenta::whereHas('venta', function($q) use ($hoy) {
                $q->where('fecha', $hoy);
            })
            ->selectRaw('producto_id, SUM(cantidad) as total_vendido, SUM(subtotal) as total_dinero')
            ->groupBy('producto_id')
            ->orderByDesc('total_vendido')
            ->with('producto')
            ->get();

        return view('estadisticas.index', compact(
            'totalHoy', 'totalMes', 'totalPendiente', 'porMetodo', 'porProducto'
        ));
    }
}
