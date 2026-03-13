@extends('layouts.app')

@section('topbar-title', '📦 Inventario del día')
@section('topbar-sub', \Carbon\Carbon::today()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY'))

@section('content')

<div style="max-width:560px">
    <form method="POST" action="{{ route('inventario.store') }}">
        @csrf
        <div class="card">
            <div class="card-header"><h3>Ingresa el stock de hoy</h3></div>
            <div class="card-body">

                @foreach($productos as $producto)
                @php
                    $inv = $inventario->get($producto->id);
                    $emoji = match($producto->sabor) {
                        'nutella'    => '🍫',
                        'red_velvet' => '🔴',
                        'leche_klim' => '🥛',
                        'pie_limon'  => '🍋',
                        'nucita'     => '🍬',
                        'mixto'      => '🥣',
                        default      => '🍪'
                    };
                @endphp
                <div class="inv-row">
                    <div style="display:flex;align-items:center;gap:10px">
                        <span style="font-size:1.3rem">{{ $emoji }}</span>
                        <div>
                            <div style="font-weight:700;font-size:0.9rem">{{ $producto->nombre }}</div>
                            @if($inv)
                                @if($inv->cantidad_actual <= 3)
                                    <div class="inv-stock-low">⚠ {{ $inv->cantidad_actual }} restantes de {{ $inv->cantidad_inicial }}</div>
                                @else
                                    <div class="inv-stock-ok">✓ {{ $inv->cantidad_actual }} restantes de {{ $inv->cantidad_inicial }}</div>
                                @endif
                            @else
                                <div class="text-muted" style="font-size:0.75rem">Sin registro hoy</div>
                            @endif
                        </div>
                    </div>
                    <input
                        type="number"
                        name="cantidades[{{ $producto->id }}]"
                        value="{{ $inv ? $inv->cantidad_inicial : 0 }}"
                        min="0"
                        class="form-control"
                        style="width:80px;text-align:center;padding:8px"
                    >
                </div>
                @endforeach

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:16px;padding:14px">
                    💾 Guardar inventario del día
                </button>
            </div>
        </div>
    </form>
</div>

@endsection