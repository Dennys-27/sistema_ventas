<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Compra;
use Exception;
use Illuminate\Support\Facades\Auth;
class Compras extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $titulo = 'Administrar Compras';
        $items = Compra::select(
            'compras.*',
            'productos.nombre as nombre_producto',
            'users.name as nombre_usuario'
        )
        ->join('users','compras.user_id','=','users.id')
        ->join('productos','compras.producto_id','=','productos.id')
        ->get(); // Assuming you have a Compra model
        return view('modules.compras.index', compact('titulo', 'items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $item = Producto::find($id);

        if (!$item) {
            return redirect()->route('productos')->with('error', 'Producto no encontrado'); 
        }else {
            $titulo = 'Nueva Compra';
            return view('modules.compras.create', compact('titulo', 'item'));
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
         try {
            $item = new Compra();
            $item->user_id = Auth::user()->id;
            $item->producto_id = $request->id;
            $item->cantidad = $request->cantidad;
            $item->precio_compra = $request->precio_compra;
            if ($item->save()) {
                $item = Producto::find($request->id);
                $item->cantidad = ($item->cantidad + $request->cantidad);
                $item->precio_compra = $request->precio_compra;
                $item->save();
            }

            return to_route('productos')->with('success', 'Compra exitosamente.');
        } catch (Exception $e) {
            return to_route('productos')->with('error', 'Error al realizar Comprar: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $item = Compra::select(
            'compras.*',
            'productos.nombre as nombre_producto',
            'users.name as nombre_usuario'
        )
        ->join('users','compras.user_id','=','users.id')
        ->join('productos','compras.producto_id','=','productos.id')
        ->where('compras.id', $id)
        ->first();
        if (!$item) {
            return redirect()->route('compras')->with('error', 'Compra no encontrada'); 
        } else {
            $titulo = 'Detalle de Compra';
            return view('modules.compras.show', compact('titulo', 'item'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $item = Compra::select(
            'compras.*',
            'productos.nombre as nombre_producto',
            'users.name as nombre_usuario'
        )
        ->join('users','compras.user_id','=','users.id')
        ->join('productos','compras.producto_id','=','productos.id')
        ->where('compras.id', $id)
        ->first();
       
        if (!$item) {
            return redirect()->route('compras')->with('error', 'Compra no encontrada'); 
        } else {
            $titulo = 'Editar Compra';
            return view('modules.compras.edit', compact('titulo', 'item'));
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        /*
        Si ya hicimos una venta con este producto, no seria buena idea actualizarlo
         */
        try {
            $cantidad_anterior = 0;
            $item = Compra::find($id);
            $cantidad_anterior = $item->cantidad;
            $item->cantidad = $request->cantidad;
            $item->precio_compra = $request->precio_compra;

            if ($item->save()) {
                $item = Producto::find($request->producto_id);
                //Cantidad = cantidad actual de producto menos la cantidad anterior de compra
                //cantidad = cantidad + nueva cantidad
                $cantidad_anterior = $item->cantidad - $cantidad_anterior;
                $item->cantidad = $cantidad_anterior + $request->cantidad;
                $item->save();
                return to_route('compras')->with('success', 'Compra actualizada con exitosa!');
            }
        } catch (\Throwable $th) {
            return to_route('compras')->with('error', 'No pudo actualizar la comprar!' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        try {
            $item = Compra::find($id);
            $cantidad_compra = $item->cantidad;
            if($item->delete()){
                $item = Producto::find($request->producto_id);
                $item->cantidad = $item->cantidad - $cantidad_compra;
                $item->save();
                return to_route('compras')->with('success', 'Compra eliminada con exito!');
            } else {
                return to_route('compras')->with('error', 'Compra no se elimino!');
            }

        } catch (\Throwable $th) {
            return to_route('compras')->with('error', 'No se pudo eliminar la compra!' . $th->getMessage());
        }
    }
}
