<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\EstadisticaController;

// Redirigir raíz al dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Ventas
Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
Route::get('/ventas/create', [VentaController::class, 'create'])->name('ventas.create');
Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');
Route::delete('/ventas/{id}', [VentaController::class, 'destroy'])->name('ventas.destroy');

// Pagos pendientes
Route::get('/pendientes', [VentaController::class, 'pendientes'])->name('ventas.pendientes');
Route::put('/pendientes/{id}/pagar', [VentaController::class, 'confirmarPago'])->name('ventas.pagar');

// Inventario
Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
Route::post('/inventario', [InventarioController::class, 'store'])->name('inventario.store');

// Estadísticas
Route::get('/estadisticas', [EstadisticaController::class, 'index'])->name('estadisticas.index');