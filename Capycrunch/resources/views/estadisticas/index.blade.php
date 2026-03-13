@extends('layouts.app')

@section('topbar-title', '📊 Estadísticas')
@section('topbar-sub', 'Resumen de ventas y recaudo')

@section('content')

<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:24px">
    <div class="stat-card" data-icon="💰">
        <div class="stat-label">Recaudado hoy</div>
        <div class="stat-value text-caramel">${{ number_format($totalHoy, 0, ',', '.') }}</div>
        <div class="stat-sub">Ventas pagadas</div>
    </div>
    <div class="stat-card" data-icon="📅">
        <div class="stat-label">Este mes</div>
        <div class="stat-value" style="color:var(--green)">${{ number_format($totalMes, 0, ',', '.') }}</div>
        <div class="stat-sub">{{ \Carbon\Carbon::today()->locale('es')->isoFormat('MMMM YYYY') }}</div>
    </div>
    <div class="stat-card" data-icon="⏳">
        <div class="stat-label">Pendiente por cobrar</div>
        <div class="stat-value" style="color:var(--brown3)">${{ number_format($totalPendiente, 0, ',', '.') }}</div>
        <div class="stat-sub">Aún no recaudado</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

    <div class="card">
        <div class="card-header"><h3>💳 Por método de pago (hoy)</h3></div>
        <div class="card-body">
            @php
                $metodos = [
                    'nequi'     => ['📱 Nequi',     'var(--caramel)',  '#e8954a'],
                    'daviplata' => ['💙 Daviplata',  '#4a90d9',         '#4a90d9'],
                    'efectivo'  => ['💵 Efectivo',   'var(--green)',    '#4a7c59'],
                ];
                $maxMetodo = $porMetodo->max() ?: 1;
            @endphp
            @foreach($metodos as $key => [$label, $color, $hex])
            @php $valor = $porMetodo[$key] ?? 0; @endphp
            <div class="bar-item">
                <div class="bar-head">
                    <span>{{ $label }}</span>
                    <span style="font-weight:700;color:{{ $color }}">${{ number_format($valor, 0, ',', '.') }}</span>
                </div>
                <div class="bar-track">
                    <div class="bar-fill" style="width:{{ $maxMetodo > 0 ? ($valor/$maxMetodo)*100 : 0 }}%;background:{{ $color }}"></div>
                </div>
            </div>
            @endforeach

            @if($porMetodo->isEmpty())
                <div class="text-muted" style="text-align:center;padding:20px">Sin ventas hoy</div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>🏆 Más vendidos (hoy)</h3></div>
        <div class="card-body">
            @if($porProducto->isEmpty())
                <div class="text-muted" style="text-align:center;padding:20px">Sin ventas hoy</div>
            @else
            @php $maxVendido = $porProducto->max('total_vendido') ?: 1; @endphp
            @foreach($porProducto as $item)
            <div class="bar-item">
                <div class="bar-head">
                    <span>{{ $item->producto->nombre }}</span>
                    <span style="font-weight:700">{{ $item->total_vendido }} uds · ${{ number_format($item->total_dinero, 0, ',', '.') }}</span>
                </div>
                <div class="bar-track">
                    <div class="bar-fill" style="width:{{ ($item->total_vendido/$maxVendido)*100 }}%;background:linear-gradient(90deg,var(--caramel),var(--brown3))"></div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>

</div>

@endsection