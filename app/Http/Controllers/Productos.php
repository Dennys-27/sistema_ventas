<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Producto;
use Exception;
use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Proveedor;


class Productos extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $items = Producto::select(
            'productos.*',
            'categorias.nombre as categoria_nombre',
            'proveedores.nombre as proveedor_nombre'
        )
        ->join('categorias','productos.categoria_id','=','categorias.id')
        ->join('proveedores','productos.proveedor_id','=','proveedores.id')
        ->get(); // Assuming you have a Producto model


        $titulo = 'Productos';
        return view('modules.productos.index', compact('titulo','items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $titulo = 'Crear Producto';
        $categorias = Categoria::all();
        $proveedores = Proveedor::all();
        return view('modules.productos.create', compact('titulo', 'categorias', 'proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //


        try {
            $item = new Producto();
            $item->nombre = $request->nombre;
            $item->user_id = Auth::user()->id;
            $item->descripcion = $request->descripcion;
            $item->categoria_id = $request->categoria_id;
            $item->proveedor_id = $request->proveedor_id;
            $item->codigo = $request->codigo;
            $item->save();
            return to_route('productos')->with('success', 'Producto creado exitosamente.');
        } catch (Exception $e) {
            return to_route('productos')->with('error', 'Error al crear el producto: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $titulo = 'Detalle Producto';
        $item = Producto::select(
            'productos.*',
            'categorias.nombre as categoria_nombre',
            'proveedores.nombre as proveedor_nombre'
        )
        ->join('categorias','productos.categoria_id','=','categorias.id')
        ->join('proveedores','productos.proveedor_id','=','proveedores.id')
        ->where('productos.id', $id)
        ->first(); 
        return view('modules.productos.show', compact('titulo', 'item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $titulo = 'Editar Producto';
        $item = Producto::findOrFail($id);
        $categorias = Categoria::all();
        $proveedores = Proveedor::all();
        return view('modules.productos.edit', compact('titulo', 'item', 'categorias', 'proveedores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        try {
            $item = Producto::findOrFail($id);
            $item->nombre = $request->nombre;
            $item->user_id = Auth::user()->id;
            $item->descripcion = $request->descripcion;
            $item->categoria_id = $request->categoria_id;
            $item->proveedor_id = $request->proveedor_id;
            $item->codigo = $request->codigo;
            $item->precio_venta = $request->precio_venta;
            $item->save();
            return to_route('productos')->with('success', 'Producto actualizado exitosamente.');
        } catch (Exception $e) {
            return to_route('productos')->with('error', 'Error al actualizar el producto: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            $item = Producto::findOrFail($id);
            $item->delete();
            return to_route('productos')->with('success', 'Producto eliminado exitosamente.');
        } catch (Exception $e) {
            return to_route('productos')->with('error', 'Error al eliminar el producto: ' . $e->getMessage());
        }
    }

    public function cambiar($id, $estado)
    {
        $producto = Producto::find($id);
        $producto->activo = $estado;

        return $producto->save();
    }


    public function tbody()
    {
        //
         $items = Producto::select(
            'productos.*',
            'categorias.nombre as categoria_nombre',
            'proveedores.nombre as proveedor_nombre'
        )
        ->join('categorias','productos.categoria_id','=','categorias.id')
        ->join('proveedores','productos.proveedor_id','=','proveedores.id')
        ->get(); // Assuming you have a Producto model


        return view('modules.productos.tbody', compact('items'));
    }
}
