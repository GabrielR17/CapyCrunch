@extends('layouts.app')

@section('topbar-title', 'Nueva Venta')
@section('topbar-sub', 'Registra los productos y el método de pago')

@section('content')

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:780px">

    {{-- COLUMNA IZQUIERDA: Productos --}}
    <div>
        <form method="POST" action="{{ route('ventas.store') }}" id="formVenta">
        @csrf

        <div class="card mb-6" style="margin-bottom:0">
            <div class="card-header"><h3>🍪 Selecciona productos</h3></div>
            <div class="card-body">

                @foreach($productos as $producto)
                <div class="product-row" id="row-{{ $producto->id }}">
                    <div style="display:flex;align-items:center">
                        <span class="product-emoji">
                            @switch($producto->sabor)
                                @case('nutella')    🍫 @break
                                @case('red_velvet') 🔴 @break
                                @case('leche_klim') 🥛 @break
                                @case('pie_limon')  🍋 @break
                                @case('nucita')     🍬 @break
                                @case('mixto')      🥣 @break
                                @default            🍪
                            @endswitch
                        </span>
                        <div>
                            <div class="product-name">{{ $producto->nombre }}</div>
                            <div class="product-price">${{ number_format($producto->precio, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="qty-ctrl">
                        <button type="button" class="qty-btn" onclick="cambiarCantidad({{ $producto->id }}, -1)">−</button>
                        <span class="qty-val" id="qty-{{ $producto->id }}">0</span>
                        <button type="button" class="qty-btn" onclick="cambiarCantidad({{ $producto->id }}, 1)">+</button>
                        <input type="hidden" name="productos[]"  value="{{ $producto->id }}">
                        <input type="hidden" name="cantidades[]" value="0" id="input-qty-{{ $producto->id }}">
                    </div>
                </div>
                @endforeach

                <div class="total-bar">
                    <div class="total-label">Total a cobrar</div>
                    <div class="total-value" id="total-display">$0</div>
                </div>
            </div>
        </div>

    {{-- COLUMNA DERECHA: dentro del mismo form --}}
        <div style="display:none" id="col-right-placeholder"></div>
        </form>
    </div>

    {{-- COLUMNA DERECHA: Cliente y pago --}}
    <div>
        {{-- Referenciamos el mismo form --}}
        <div class="card" style="margin-bottom:16px">
            <div class="card-header"><h3>👤 Cliente (opcional)</h3></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="cliente_nombre" form="formVenta" class="form-control" placeholder="Ej: María García">
                </div>
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="cliente_telefono" form="formVenta" class="form-control" placeholder="Ej: 310 234 5678">
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom:16px">
            <div class="card-header"><h3>💳 Pago</h3></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Estado del pago</label>
                    <div style="display:flex;gap:8px">
                        <label id="lbl-pagada" style="flex:1;padding:10px;background:var(--green2);border:2px solid var(--green);border-radius:10px;cursor:pointer;display:flex;align-items:center;gap:6px;font-size:0.82rem;font-weight:700;color:var(--green)">
                            <input type="radio" name="estado_pago" form="formVenta" value="pagada" checked onchange="togglePago()"> ✅ Paga ahora
                        </label>
                        <label id="lbl-pendiente" style="flex:1;padding:10px;background:var(--cream);border:2px solid var(--border);border-radius:10px;cursor:pointer;display:flex;align-items:center;gap:6px;font-size:0.82rem;font-weight:700;color:var(--muted)">
                            <input type="radio" name="estado_pago" form="formVenta" value="pendiente" onchange="togglePago()"> ⏳ Pendiente
                        </label>
                    </div>
                </div>
                <div class="form-group" id="metodo-wrap">
                    <label class="form-label">Método de pago</label>
                    <select name="metodo_pago" form="formVenta" class="form-control">
                        <option value="">Selecciona...</option>
                        <option value="nequi">📱 Nequi</option>
                        <option value="daviplata">💙 Daviplata</option>
                        <option value="efectivo">💵 Efectivo</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label">Nota (opcional)</label>
                    <input type="text" name="nota" form="formVenta" class="form-control" placeholder="Ej: cliente habitual">
                </div>
            </div>
        </div>

        <div style="display:flex;gap:10px">
            <a href="{{ route('dashboard') }}" class="btn btn-outline" style="flex:1;justify-content:center">← Cancelar</a>
            <button type="submit" form="formVenta" class="btn btn-primary" style="flex:2;justify-content:center">✓ Registrar Venta</button>
        </div>
    </div>

</div>

<script>
const precios = {
    @foreach($productos as $p) {{ $p->id }}: {{ $p->precio }}, @endforeach
};
const cantidades = {};
@foreach($productos as $p) cantidades[{{ $p->id }}] = 0; @endforeach

function cambiarCantidad(id, delta) {
    cantidades[id] = Math.max(0, (cantidades[id] || 0) + delta);
    document.getElementById('qty-' + id).textContent = cantidades[id];
    document.getElementById('input-qty-' + id).value = cantidades[id];
    // Resaltar fila si tiene cantidad
    const row = document.getElementById('row-' + id);
    if (cantidades[id] > 0) {
        row.style.borderColor = 'var(--caramel)';
        row.style.background = '#fff9f2';
    } else {
        row.style.borderColor = 'var(--border)';
        row.style.background = 'var(--cream)';
    }
    actualizarTotal();
}

function actualizarTotal() {
    let total = 0;
    for (const id in cantidades) total += cantidades[id] * precios[id];
    document.getElementById('total-display').textContent = '$' + total.toLocaleString('es-CO');
}

function togglePago() {
    const val = document.querySelector('input[name="estado_pago"]:checked').value;
    const esPagada = val === 'pagada';
    document.getElementById('metodo-wrap').style.display = esPagada ? 'block' : 'none';
    document.getElementById('lbl-pagada').style.background    = esPagada ? 'var(--green2)' : 'var(--cream)';
    document.getElementById('lbl-pagada').style.borderColor   = esPagada ? 'var(--green)' : 'var(--border)';
    document.getElementById('lbl-pagada').style.color         = esPagada ? 'var(--green)' : 'var(--muted)';
    document.getElementById('lbl-pendiente').style.background = !esPagada ? '#fff3e0' : 'var(--cream)';
    document.getElementById('lbl-pendiente').style.borderColor= !esPagada ? 'var(--brown3)' : 'var(--border)';
    document.getElementById('lbl-pendiente').style.color      = !esPagada ? 'var(--brown3)' : 'var(--muted)';
}
</script>

@endsection