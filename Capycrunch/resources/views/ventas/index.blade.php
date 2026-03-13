@extends('layouts.app')

@section('topbar-title', 'Historial de Ventas')
@section('topbar-sub', 'Registro de todas las ventas pagadas')
@section('topbar-action')
    <a href="{{ route('ventas.create') }}" class="btn btn-primary">🛒 Nueva Venta</a>
@endsection

@section('content')

<div class="table-wrap">
    @if($ventas->isEmpty())
        <div class="empty-state">
            <div style="font-size:2.5rem;margin-bottom:8px">📋</div>
            Sin ventas registradas aún
        </div>
    @else
    <table>
        <thead>
            <tr>
                <th>Fecha y hora</th>
                <th>Cliente</th>
                <th>Productos</th>
                <th>Método</th>
                <th>Total</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $venta)
            <tr>
                <td class="text-muted">{{ \Carbon\Carbon::parse($venta->created_at)->format('d/m/Y h:i a') }}</td>
                <td style="font-weight:600">{{ $venta->cliente ? $venta->cliente->nombre : '—' }}</td>
                <td style="font-size:0.82rem;color:var(--brown2)">
                    {{ $venta->detalles->filter(fn($d) => $d->cantidad > 0)->map(fn($d) => $d->producto->nombre.' x'.$d->cantidad)->join(' · ') }}
                </td>
                <td>
                    <span class="badge badge-{{ $venta->metodo_pago }}">
                        @switch($venta->metodo_pago)
                            @case('nequi') 📱 @break
                            @case('daviplata') 💙 @break
                            @case('efectivo') 💵 @break
                        @endswitch
                        {{ ucfirst($venta->metodo_pago) }}
                    </span>
                </td>
                <td style="color:var(--caramel);font-weight:700">${{ number_format($venta->total, 0, ',', '.') }}</td>
                <td>
                    <form method="POST" action="{{ route('ventas.destroy', $venta->id) }}" onsubmit="return confirm('¿Eliminar esta venta? El stock se devolverá al inventario.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">✕ Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

@endsection