<?php
namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\Producto;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with(['cliente', 'detalles.producto'])
            ->where('estado_pago', 'pagada')
            ->orderByDesc('created_at')
            ->get();
        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $productos = Producto::where('activo', true)->get();
        return view('ventas.create', compact('productos'));
    }

    public function store(Request $request)
{
    $request->validate([
        'productos'   => 'required|array|min:1',
        'cantidades'  => 'required|array|min:1',
        'estado_pago' => 'required|in:pagada,pendiente',
    ]);

    $hoy = \Carbon\Carbon::today();

    // Filtrar solo productos con cantidad > 0
    $productosSeleccionados = [];
    foreach ($request->productos as $i => $productoId) {
        $cantidad = (int) $request->cantidades[$i];
        if ($cantidad > 0) {
            $productosSeleccionados[] = [
                'id'       => $productoId,
                'cantidad' => $cantidad,
            ];
        }
    }

    if (empty($productosSeleccionados)) {
        return redirect()->back()
            ->with('error', 'Debes seleccionar al menos un producto con cantidad mayor a 0.');
    }

    // Validar stock disponible en inventario
    foreach ($productosSeleccionados as $item) {
        $inventario = \App\Models\Inventario::where('producto_id', $item['id'])
            ->where('fecha', $hoy)
            ->first();

        $producto = \App\Models\Producto::find($item['id']);

        if (!$inventario) {
            return redirect()->back()
                ->with('error', "No hay inventario registrado hoy para \"{$producto->nombre}\". Registra el inventario primero.");
        }

        if ($item['cantidad'] > $inventario->cantidad_actual) {
            return redirect()->back()
                ->with('error', "Stock insuficiente para \"{$producto->nombre}\". Disponible: {$inventario->cantidad_actual}, solicitado: {$item['cantidad']}.");
        }
    }

    // Crear cliente si se ingresaron datos
    $clienteId = null;
    if ($request->filled('cliente_nombre')) {
        $cliente = \App\Models\Cliente::create([
            'nombre'   => $request->cliente_nombre,
            'telefono' => $request->cliente_telefono,
        ]);
        $clienteId = $cliente->id;
    }

    // Calcular total
    $total = 0;
    foreach ($productosSeleccionados as $item) {
        $producto = \App\Models\Producto::find($item['id']);
        $total += $producto->precio * $item['cantidad'];
    }

    // Crear la venta
    $venta = \App\Models\Venta::create([
        'cliente_id'  => $clienteId,
        'fecha'       => $hoy,
        'total'       => $total,
        'metodo_pago' => $request->estado_pago === 'pagada' ? $request->metodo_pago : null,
        'estado_pago' => $request->estado_pago,
        'fecha_pago'  => $request->estado_pago === 'pagada' ? \Carbon\Carbon::now() : null,
        'nota'        => $request->nota,
    ]);

    // Crear detalles y descontar inventario
    foreach ($productosSeleccionados as $item) {
        $producto = \App\Models\Producto::find($item['id']);

        \App\Models\DetalleVenta::create([
            'venta_id'        => $venta->id,
            'producto_id'     => $item['id'],
            'cantidad'        => $item['cantidad'],
            'precio_unitario' => $producto->precio,
            'subtotal'        => $producto->precio * $item['cantidad'],
        ]);

        $inventario = \App\Models\Inventario::where('producto_id', $item['id'])
            ->where('fecha', $hoy)
            ->first();
        $inventario->decrement('cantidad_actual', $item['cantidad']);
    }

    return redirect()->route('ventas.index')
        ->with('success', 'Venta registrada correctamente.');
}

    public function destroy($id)
    {
        $venta = Venta::findOrFail($id);
        // Devolver stock al inventario
        foreach ($venta->detalles as $detalle) {
            $inventario = \App\Models\Inventario::where('producto_id', $detalle->producto_id)
                ->where('fecha', $venta->fecha)
                ->first();
            if ($inventario) {
                $inventario->increment('cantidad_actual', $detalle->cantidad);
            }
        }
        $venta->delete();
        return redirect()->route('ventas.index')
            ->with('success', 'Venta eliminada correctamente');
    }

    public function pendientes()
    {
        $pendientes = Venta::with(['cliente', 'detalles.producto'])
            ->where('estado_pago', 'pendiente')
            ->orderByDesc('created_at')
            ->get();
        $totalPendiente = $pendientes->sum('total');
        return view('ventas.pendientes', compact('pendientes', 'totalPendiente'));
    }

    public function confirmarPago(Request $request, $id)
    {
        $request->validate([
            'metodo_pago' => 'required|in:nequi,daviplata,efectivo',
        ]);

        $venta = Venta::findOrFail($id);
        $venta->update([
            'estado_pago' => 'pagada',
            'metodo_pago' => $request->metodo_pago,
            'fecha_pago'  => Carbon::now(),
        ]);

        return redirect()->route('ventas.pendientes')
            ->with('success', 'Pago confirmado correctamente');
    }
}