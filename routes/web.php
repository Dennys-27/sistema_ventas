<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ventas;
use App\Http\Controllers\DetalleVentas;
use App\Http\Controllers\Categorias;
use App\Http\Controllers\Productos;
use App\Http\Controllers\Proveedores;
use App\Http\Controllers\Usuarios;

/* Route::get('/', function () {
    return view('welcome');
}); */
//crear un usuario administrador
//Route::get('/crear-admin', [AuthController::class, 'crearAdmin']);


Route::middleware(['auth'])->group(function () {
    Route::get('/home', [Dashboard::class, 'index'])->name('home');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/logear', [AuthController::class, 'logear'])->name('logear');


































Route::prefix('ventas')->middleware('auth')->group(function () {
    Route::get('/nueva-venta', [Ventas::class, 'index'])->name('ventas-nueva');
});

Route::prefix('detalle')->middleware('auth')->group(function () {
    Route::get('/detalle-venta', [DetalleVentas::class, 'index'])->name('detalle-venta');
});

Route::prefix('categorias')->middleware('auth')->group(function () {
    Route::get('/', [Categorias::class, 'index'])->name('categorias');
    Route::get('/create', [Categorias::class, 'create'])->name('categorias-create');
    Route::post('/store', [Categorias::class, 'store'])->name('categorias-store');
    Route::get('/show/{id}', [Categorias::class, 'show'])->name('categorias-show');
    Route::get('/edit/{id}', [Categorias::class, 'edit'])->name('categorias-edit');
    Route::delete('/destroy/{id}', [Categorias::class, 'destroy'])->name('categorias-destroy');
    Route::put('/update/{id}', [Categorias::class, 'update'])->name('categorias-update');
});



Route::prefix('productos')->middleware('auth')->group(function () {
    Route::get('/', [Productos::class, 'index'])->name('productos');
    
});


Route::prefix('proveedores')->middleware('auth')->group(function () {
    Route::get('/', [Proveedores::class, 'index'])->name('proveedores');
    Route::get('/edit/{id}', [Proveedores::class, 'edit'])->name('proveedores-edit');
    Route::get('/show/{id}',  [Proveedores::class,  'show'])->name('proveedores-show');
    Route::get('/create', [Proveedores::class, 'create'])->name('proveedores-create');
    Route::post('/store', [Proveedores::class, 'store'])->name('proveedores-store');
    Route::delete('/destroy/{id}', [Proveedores::class, 'destroy'])->name('proveedores-destroy');
    Route::put('/update/{id}', [Proveedores::class, 'update'])->name('proveedores-update');
});

Route::prefix('usuarios')->middleware('auth')->group(function () {
    Route::get('/', [Usuarios::class, 'index'])->name('usuarios');
    Route::get('/create', [Usuarios::class, 'create'])->name('usuarios-create');
    Route::post('/store', [Usuarios::class, 'store'])->name('usuarios-store');
    Route::get('/edit/{id}', [Usuarios::class, 'edit'])->name('usuarios-edit');
    Route::put('/update/{id}', [Usuarios::class, 'update'])->name('usuarios-update');
    Route::get('/tbody', [Usuarios::class, 'tbody'])->name('usuarios-tbody');
    Route::get('/activar/{id}/{estado}', [Usuarios::class, 'activar'])->name('usuarios-activar');
    Route::get('/cambiar-password/{id}/{password}', [Usuarios::class, 'cambio_password'])->name('cambiar-password');
});
