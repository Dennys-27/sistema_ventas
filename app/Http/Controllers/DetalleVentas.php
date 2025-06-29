<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DetalleVentas extends Controller
{
    //
    public function index()
    {
        $titulo = 'Detalle de Ventas';
        return view('modules.detalle_ventas.index', compact('titulo'));
    }
}
