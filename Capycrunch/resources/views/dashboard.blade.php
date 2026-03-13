@extends('layouts.app')

@section('topbar-title', 'Buen día, vendedor 👋')
@section('topbar-sub', \Carbon\Carbon::today()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY'))
@section('topbar-action')
    <a href="{{ route('ventas.create') }}" class="btn btn-primary">🛒 Nueva Venta</a>
@endsection

@section('content')

<div class="stats-grid">
    <div class="stat-card" data-icon="💰">
        <div class="stat-label">Recaudado hoy</div>
        <div class="stat-value text-caramel">${{ number_format($recaudadoHoy, 0, ',', '.') }}</div>
        <div class="stat-sub">Solo ventas pagadas</div>
    </div>
    <div class="stat-card" data-icon="⏳">
        <div class="stat-label">Por cobrar</div>
        <div class="stat-value" style="color:var(--brown3)">${{ number_format($totalPendiente, 0, ',', '.') }}</div>
        <div class="stat-sub">{{ $pendientes->count() }} ventas pendientes</div>
    </div>
    <div class="stat-card" data-icon="📦">
        <div class="stat-label">Stock disponible</div>
        <div class="stat-value">{{ $stockHoy }}</div>
        <div class="stat-sub">Unidades totales hoy</div>
    </div>
    <div class="stat-card" data-icon="🏆">
        <div class="stat-label">Producto top</div>
        <div class="stat-value" style="font-size:1.1rem">
            {{ $productoTop ? $productoTop->producto->nombre : 'Sin datos' }}
        </div>
        <div class="stat-sub">
            {{ $productoTop ? $productoTop->total_vendido . ' vendidas hoy' : '' }}
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">

    @if($pendientes->count() > 0)
    <div class="card">
        <div class="card-header">
            <h3>⏳ Pagos pendientes</h3>
            <a href="{{ route('ventas.pendientes') }}" class="btn btn-outline btn-sm">Ver todos →</a>
        </div>
        <div style="padding:0">
            <table>
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendientes->take(4) as $p)
                    <tr>
                        <td style="font-weight:700">{{ $p->cliente ? $p->cliente->nombre : 'Sin nombre' }}</td>
                        <td class="text-muted">{{ $p->cliente ? $p->cliente->telefono : '—' }}</td>
                        <td style="color:var(--caramel);font-weight:700">${{ number_format($p->total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="card" style="display:flex;align-items:center;justify-content:center">
        <div class="empty-state">
            <div style="font-size:2.5rem;margin-bottom:8px">✅</div>
            <div style="font-weight:700">Sin pagos pendientes</div>
            <div class="text-muted" style="margin-top:4px">Todo está al día</div>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-header"><h3>⚡ Acciones rápidas</h3></div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px">
                <a href="{{ route('ventas.create') }}" style="text-align:center;padding:18px;background:var(--cream);border:1.5px solid var(--border);border-radius:var(--radius);text-decoration:none;transition:all 0.2s;display:block" onmouseover="this.style.borderColor='var(--caramel)';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='var(--border)';this.style.transform='none'">
                    <div style="font-size:2rem;margin-bottom:8px">🛒</div>
                    <div style="font-weight:700;font-size:0.85rem;color:var(--brown)">Nueva Venta</div>
                </a>
                <a href="{{ route('inventario.index') }}" style="text-align:center;padding:18px;background:var(--cream);border:1.5px solid var(--border);border-radius:var(--radius);text-decoration:none;transition:all 0.2s;display:block" onmouseover="this.style.borderColor='var(--caramel)';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='var(--border)';this.style.transform='none'">
                    <div style="font-size:2rem;margin-bottom:8px">📦</div>
                    <div style="font-weight:700;font-size:0.85rem;color:var(--brown)">Inventario</div>
                </a>
                <a href="{{ route('estadisticas.index') }}" style="text-align:center;padding:18px;background:var(--cream);border:1.5px solid var(--border);border-radius:var(--radius);text-decoration:none;transition:all 0.2s;display:block" onmouseover="this.style.borderColor='var(--caramel)';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='var(--border)';this.style.transform='none'">
                    <div style="font-size:2rem;margin-bottom:8px">📊</div>
                    <div style="font-weight:700;font-size:0.85rem;color:var(--brown)">Estadísticas</div>
                </a>
            </div>
        </div>
    </div>

</div>

<div class="table-wrap">
    <div class="table-head">
        <h3>📋 Últimas ventas del día</h3>
    </div>
    @php
        $ultimasVentas = \App\Models\Venta::with(['cliente','detalles.producto'])
            ->whereDate('fecha', \Carbon\Carbon::today())
            ->where('estado_pago','pagada')
            ->orderByDesc('created_at')->take(5)->get();
    @endphp
    @if($ultimasVentas->isEmpty())
        <div class="empty-state">Sin ventas registradas hoy</div>
    @else
    <table>
        <thead>
            <tr>
                <th>Hora</th>
                <th>Productos</th>
                <th>Cliente</th>
                <th>Método</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ultimasVentas as $v)
            <tr>
                <td class="text-muted">{{ \Carbon\Carbon::parse($v->created_at)->format('h:i a') }}</td>
                <td>{{ $v->detalles->map(fn($d) => $d->producto->nombre.' x'.$d->cantidad)->join(' · ') }}</td>
                <td>{{ $v->cliente ? $v->cliente->nombre : '—' }}</td>
                <td><span class="badge badge-{{ $v->metodo_pago }}">{{ ucfirst($v->metodo_pago) }}</span></td>
                <td style="color:var(--caramel);font-weight:700">${{ number_format($v->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

@endsection