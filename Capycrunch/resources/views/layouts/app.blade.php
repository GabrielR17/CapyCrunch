<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🍪 CapyCrunch</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --cream:    #fdf6ec;
            --cream2:   #f7ead8;
            --cream3:   #eedfc4;
            --brown:    #5c3d1e;
            --brown2:   #8b5e3c;
            --brown3:   #c4874a;
            --caramel:  #e8954a;
            --caramel2: #f0a85c;
            --warm:     #fff8f0;
            --green:    #4a7c59;
            --green2:   #e8f5ee;
            --red:      #c0392b;
            --red2:     #fdecea;
            --muted:    #9b8878;
            --border:   #e8d9c8;
            --shadow:   0 2px 12px rgba(92,61,30,0.08);
            --shadow2:  0 4px 24px rgba(92,61,30,0.12);
            --radius:   16px;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Nunito', sans-serif;
            background: var(--cream);
            color: var(--brown);
            min-height: 100vh;
            display: flex;
        }

        /* ─── SIDEBAR ─── */
        .sidebar {
            width: 230px;
            flex-shrink: 0;
            background: var(--brown);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
            overflow: hidden;
        }
        .sidebar::before {
            content: '';
            position: absolute; bottom: -60px; right: -60px;
            width: 200px; height: 200px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
        }
        .sidebar::after {
            content: '';
            position: absolute; top: -40px; left: -40px;
            width: 160px; height: 160px;
            background: rgba(255,255,255,0.02);
            border-radius: 50%;
        }
        .sidebar-logo {
            padding: 28px 22px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            position: relative; z-index: 1;
        }
        .sidebar-logo .logo-icon { font-size: 2rem; display: block; margin-bottom: 6px; }
        .sidebar-logo h2 {
            font-family: 'Playfair Display', serif;
            color: var(--caramel2); font-size: 1.3rem; font-weight: 800; line-height: 1;
        }
        .sidebar-logo p { color: rgba(255,255,255,0.4); font-size: 0.72rem; margin-top: 3px; }
        .sidebar-nav {
            padding: 16px 12px; flex: 1;
            display: flex; flex-direction: column; gap: 3px;
            position: relative; z-index: 1;
        }
        .nav-section-label {
            font-size: 0.65rem; font-weight: 800; letter-spacing: 0.12em;
            text-transform: uppercase; color: rgba(255,255,255,0.3);
            padding: 10px 10px 6px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 10px;
            color: rgba(255,255,255,0.6);
            font-size: 0.875rem; font-weight: 600;
            text-decoration: none; transition: all 0.2s;
        }
        .nav-item:hover { background: rgba(255,255,255,0.08); color: white; }
        .nav-item.active {
            background: var(--caramel); color: white;
            box-shadow: 0 4px 14px rgba(232,149,74,0.4);
        }
        .nav-icon { font-size: 1.05rem; width: 22px; text-align: center; }
        .nav-badge {
            margin-left: auto; background: #e74c3c;
            color: white; font-size: 0.65rem; font-weight: 800;
            width: 18px; height: 18px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }
        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.3); font-size: 0.72rem;
            position: relative; z-index: 1;
        }

        /* ─── MAIN ─── */
        .main-wrapper {
            margin-left: 230px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .topbar {
            background: white;
            border-bottom: 1px solid var(--border);
            padding: 14px 28px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-title { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 700; }
        .topbar-date { color: var(--muted); font-size: 0.82rem; }
        .content {
            padding: 28px; flex: 1;
            animation: fadeIn 0.3s ease;
        }

        /* ─── PAGE HEADER ─── */
        .page-header {
            display: flex; align-items: flex-start; justify-content: space-between;
            margin-bottom: 24px;
        }
        .page-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.7rem; font-weight: 800; color: var(--brown); line-height: 1.2;
        }
        .page-header p { color: var(--muted); font-size: 0.875rem; margin-top: 4px; }

        /* ─── STAT CARDS ─── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px; margin-bottom: 24px;
        }
        .stat-card {
            background: white; border: 1px solid var(--border);
            border-radius: var(--radius); padding: 20px;
            box-shadow: var(--shadow); position: relative; overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow2); }
        .stat-card::before {
            content: attr(data-icon);
            position: absolute; right: 14px; top: 14px;
            font-size: 1.8rem; opacity: 0.12;
        }
        .stat-label {
            font-size: 0.72rem; font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.07em; color: var(--muted); margin-bottom: 8px;
        }
        .stat-value {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem; font-weight: 700; color: var(--brown);
        }
        .stat-sub { font-size: 0.75rem; color: var(--muted); margin-top: 4px; }
        .stat-bar { margin-top: 10px; height: 4px; background: var(--cream2); border-radius: 2px; overflow: hidden; }
        .stat-bar-fill { height: 100%; border-radius: 2px; }

        /* ─── CARDS ─── */
        .card {
            background: white; border: 1px solid var(--border);
            border-radius: var(--radius); box-shadow: var(--shadow);
        }
        .card-header {
            padding: 18px 22px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-header h3 { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; }
        .card-body { padding: 22px; }

        /* ─── TABLE ─── */
        .table-wrap {
            background: white; border: 1px solid var(--border);
            border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden;
        }
        .table-head {
            padding: 18px 22px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .table-head h3 { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: var(--cream); }
        th {
            padding: 13px 18px; text-align: left;
            font-size: 0.7rem; font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.08em; color: var(--muted);
            border-bottom: 2px solid var(--border);
        }
        td { padding: 14px 18px; font-size: 0.875rem; border-bottom: 1px solid var(--border); }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: var(--warm); }

        /* ─── BADGES ─── */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 4px 10px; border-radius: 20px;
            font-size: 0.72rem; font-weight: 800;
        }
        .badge-pagada   { background: var(--green2); color: var(--green); }
        .badge-pendiente{ background: #fff3e0; color: var(--brown3); }
        .badge-nequi    { background: #fff8e7; color: #c87d00; }
        .badge-daviplata{ background: #e8f0fe; color: #1a6cc4; }
        .badge-efectivo { background: var(--green2); color: var(--green); }

        /* ─── BOTONES ─── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 10px 20px; border-radius: 10px; cursor: pointer;
            font-family: 'Nunito', sans-serif; font-size: 0.875rem;
            font-weight: 700; text-decoration: none; border: none;
            transition: all 0.2s;
        }
        .btn-primary { background: var(--caramel); color: white; box-shadow: 0 4px 14px rgba(232,149,74,0.35); }
        .btn-primary:hover { background: var(--brown3); transform: translateY(-1px); }
        .btn-success { background: var(--green2); color: var(--green); border: 1px solid #b7dfc5; }
        .btn-success:hover { background: #d4eddf; }
        .btn-danger  { background: var(--red2); color: var(--red); border: 1px solid #f5c6c2; }
        .btn-danger:hover { background: #fbd5d2; }
        .btn-outline { background: white; color: var(--brown2); border: 1px solid var(--border); }
        .btn-outline:hover { background: var(--cream); }
        .btn-sm { padding: 6px 12px; font-size: 0.78rem; }

        /* ─── FORMS ─── */
        .form-group { margin-bottom: 18px; }
        .form-label {
            display: block; font-size: 0.78rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.06em;
            color: var(--brown2); margin-bottom: 7px;
        }
        .form-control {
            width: 100%; padding: 11px 14px;
            background: var(--cream); border: 1.5px solid var(--border);
            border-radius: 10px; color: var(--brown);
            font-family: 'Nunito', sans-serif; font-size: 0.9rem; outline: none;
            transition: all 0.2s;
        }
        .form-control:focus {
            border-color: var(--caramel); background: white;
            box-shadow: 0 0 0 3px rgba(232,149,74,0.12);
        }
        select.form-control option { background: white; }

        /* ─── ALERTS ─── */
        .alert {
            padding: 13px 18px; border-radius: 10px; margin-bottom: 20px;
            font-size: 0.875rem; font-weight: 600;
            display: flex; align-items: center; gap: 8px;
        }
        .alert-success { background: var(--green2); color: var(--green); border: 1px solid #b7dfc5; }
        .alert-danger  { background: var(--red2); color: var(--red); border: 1px solid #f5c6c2; }

        /* ─── TOTAL BAR ─── */
        .total-bar {
            background: linear-gradient(135deg, #fff3e0, #ffecd2);
            border: 1.5px solid #f5c87a; border-radius: 12px;
            padding: 16px 20px;
            display: flex; justify-content: space-between; align-items: center;
            margin: 16px 0;
        }
        .total-label { font-size: 0.8rem; color: var(--brown2); font-weight: 600; }
        .total-value {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem; font-weight: 700; color: var(--caramel);
        }

        /* ─── PRODUCT ROW ─── */
        .product-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 16px; border-radius: 10px;
            background: var(--cream); border: 1.5px solid var(--border);
            margin-bottom: 8px; transition: border-color 0.2s;
        }
        .product-row:hover { border-color: var(--caramel2); }
        .product-emoji { font-size: 1.4rem; margin-right: 10px; }
        .product-name { font-weight: 700; font-size: 0.9rem; }
        .product-price { color: var(--caramel); font-weight: 700; font-size: 0.82rem; }
        .qty-ctrl { display: flex; align-items: center; gap: 8px; }
        .qty-btn {
            width: 28px; height: 28px; border-radius: 8px;
            background: white; border: 1.5px solid var(--border);
            color: var(--brown); font-size: 1rem; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.15s; font-weight: 700; font-family: 'Nunito', sans-serif;
        }
        .qty-btn:hover { background: var(--caramel); color: white; border-color: var(--caramel); }
        .qty-val { font-weight: 800; font-size: 0.9rem; min-width: 20px; text-align: center; }

        /* ─── PENDING CARD ─── */
        .pending-card {
            background: white; border: 1.5px solid var(--border);
            border-left: 4px solid var(--caramel);
            border-radius: var(--radius); padding: 18px;
            margin-bottom: 12px; box-shadow: var(--shadow);
            transition: box-shadow 0.2s;
        }
        .pending-card:hover { box-shadow: var(--shadow2); }

        /* ─── BAR CHART ─── */
        .bar-item { margin-bottom: 14px; }
        .bar-head { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 0.85rem; }
        .bar-track { height: 8px; background: var(--cream2); border-radius: 4px; overflow: hidden; }
        .bar-fill { height: 100%; border-radius: 4px; }

        /* ─── INVENTORY ─── */
        .inv-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 13px 16px; background: var(--cream);
            border: 1.5px solid var(--border); border-radius: 10px; margin-bottom: 8px;
        }
        .inv-stock-ok  { color: var(--green); font-weight: 700; font-size: 0.8rem; }
        .inv-stock-low { color: var(--red);   font-weight: 700; font-size: 0.8rem; }

        /* ─── UTILS ─── */
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .mb-4 { margin-bottom: 16px; }
        .mb-6 { margin-bottom: 24px; }
        .text-muted { color: var(--muted); font-size: 0.85rem; }
        .text-caramel { color: var(--caramel); }
        .fw-bold { font-family: 'Playfair Display', serif; font-weight: 700; }
        .empty-state { padding: 50px; text-align: center; color: var(--muted); font-size: 0.9rem; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo">
        <span class="logo-icon">🍪</span>
        <h2>CapyCrunch</h2>
        <p>Panel del vendedor</p>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section-label">Principal</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span> Dashboard
        </a>
        <div class="nav-section-label">Ventas</div>
        <a href="{{ route('ventas.create') }}" class="nav-item {{ request()->routeIs('ventas.create') ? 'active' : '' }}">
            <span class="nav-icon">🛒</span> Nueva Venta
        </a>
        <a href="{{ route('ventas.index') }}" class="nav-item {{ request()->routeIs('ventas.index') ? 'active' : '' }}">
            <span class="nav-icon">📋</span> Historial
        </a>
        <a href="{{ route('ventas.pendientes') }}" class="nav-item {{ request()->routeIs('ventas.pendientes') ? 'active' : '' }}">
            <span class="nav-icon">⏳</span> Pendientes
            @php $countPendientes = \App\Models\Venta::where('estado_pago','pendiente')->count(); @endphp
            @if($countPendientes > 0)
                <span class="nav-badge">{{ $countPendientes }}</span>
            @endif
        </a>
        <div class="nav-section-label">Gestión</div>
        <a href="{{ route('inventario.index') }}" class="nav-item {{ request()->routeIs('inventario.*') ? 'active' : '' }}">
            <span class="nav-icon">📦</span> Inventario
        </a>
        <a href="{{ route('estadisticas.index') }}" class="nav-item {{ request()->routeIs('estadisticas.*') ? 'active' : '' }}">
            <span class="nav-icon">📊</span> Estadísticas
        </a>
    </nav>
    <div class="sidebar-footer">UTS 2026</div>
</aside>

<div class="main-wrapper">
    <div class="topbar">
        <div>
            <div class="topbar-title">@yield('topbar-title', 'GalletApp')</div>
            <div class="topbar-date">@yield('topbar-sub', \Carbon\Carbon::today()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY'))</div>
        </div>
        <div>@yield('topbar-action')</div>
    </div>

    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">❌ {{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

</body>
</html>