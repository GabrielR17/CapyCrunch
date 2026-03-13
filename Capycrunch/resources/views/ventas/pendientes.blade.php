@extends('layouts.app')

@section('topbar-title', '⏳ Pagos Pendientes')
@section('topbar-sub', 'Ventas donde el cliente aún no ha pagado')

@section('content')

<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;max-width:380px;margin-bottom:24px">
    <div class="stat-card" data-icon="💰">
        <div class="stat-label">Total por cobrar</div>
        <div class="stat-value text-caramel">${{ number_format($totalPendiente, 0, ',', '.') }}</div>
    </div>
    <div class="stat-card" data-icon="👥">
        <div class="stat-label">Clientes</div>
        <div class="stat-value">{{ $pendientes->count() }}</div>
    </div>
</div>

@if($pendientes->isEmpty())
    <div class="card" style="max-width:500px">
        <div class="empty-state">
            <div style="font-size:3rem;margin-bottom:10px">✅</div>
            <div style="font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;margin-bottom:4px">¡Todo cobrado!</div>
            <div class="text-muted">No tienes pagos pendientes por el momento</div>
        </div>
    </div>
@else
<div style="max-width:580px">
    @foreach($pendientes as $venta)
    <div class="pending-card">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px">
            <div>
                <div style="font-weight:800;font-size:1rem">
                    {{ $venta->cliente ? $venta->cliente->nombre : 'Sin nombre' }}
                </div>
                <div class="text-muted" style="font-size:0.8rem">
                    @if($venta->cliente && $venta->cliente->telefono)
                        📞 {{ $venta->cliente->telefono }} ·
                    @endif
                    {{ \Carbon\Carbon::parse($venta->created_at)->locale('es')->diffForHumans() }}
                </div>
            </div>
            <div style="font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--caramel)">
                ${{ number_format($venta->total, 0, ',', '.') }}
            </div>
        </div>

        <div style="font-size:0.8rem;color:var(--muted);padding:8px 12px;background:var(--cream);border-radius:8px;margin-bottom:12px">
            {{ $venta->detalles->map(fn($d) => $d->producto->nombre.' x'.$d->cantidad)->join(' · ') }}
        </div>

        @if($venta->nota)
        <div style="font-size:0.78rem;color:var(--brown2);padding:6px 12px;margin-bottom:12px;font-style:italic">
            💬 {{ $venta->nota }}
        </div>
        @endif

        <form method="POST" action="{{ route('ventas.pagar', $venta->id) }}">
            @csrf
            @method('PUT')
            <div style="display:flex;gap:8px">
                <select name="metodo_pago" class="form-control" style="flex:1" required>
                    <option value="">Selecciona método de pago...</option>
                    <option value="nequi">📱 Nequi</option>
                    <option value="daviplata">💙 Daviplata</option>
                    <option value="efectivo">💵 Efectivo</option>
                </select>
                <button type="submit" class="btn btn-success">✓ Confirmar pago</button>
            </div>
        </form>
    </div>
    @endforeach
</div>
@endif

@endsection