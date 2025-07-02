<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Producto;
use App\Models\Imagen;
use Exception;
use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Storage;

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
            'categorias.nombre as nombre_categoria',
            'proveedores.nombre as nombre_proveedor',
            'imagenes.ruta as imagen_producto',
            'imagenes.id as imagen_id' 
        )
        ->join('categorias', 'productos.categoria_id', '=' , 'categorias.id')
        ->join('proveedores', 'productos.proveedor_id', '=' , 'proveedores.id')
        ->leftJoin('imagenes', 'productos.id', '=', 'imagenes.producto_id')
        ->get();


        $titulo = 'Productos';
        return view('modules.productos.index', compact('titulo', 'items'));
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
            $id_producto = $item->id;

            if ($id_producto > 0) {
                if ($this->subirImagen($request, $id_producto)) {
                    return to_route('productos')->with('success', 'Producto creado exitosamente.');
                } else {
                    return to_route('productos')->with('error', 'No se subio la imagen!!');
                }
            }
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
            'proveedores.nombre as proveedor_nombre',

        )
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->join('proveedores', 'productos.proveedor_id', '=', 'proveedores.id')

            ->where('productos.id', $id)
            ->first();
        return view('modules.productos.show', compact('titulo', 'item'));
    }

    public function show_image($id)
    {
        $titulo = 'Editar imagen';
        $item = Imagen::find($id);
        return view('modules.productos.show-image', compact('titulo', 'item'));
    }

    public function update_image(Request $request, $id)
    {
        try {
            $item = Imagen::find($id);
            if ($item->ruta && Storage::disk('public')->exists($item->ruta)) {
                Storage::disk('public')->delete($item->ruta);
           
            }

            $rutaImagen = $request->file('imagen')->store('imagenes', 'public');
            $nombreImagen = basename($rutaImagen);
            $item->nombre = $nombreImagen;
            $item->ruta = $rutaImagen;
            $item->save();
            return to_route('productos')->with('success', 'Imagen actualizada exitosamente.');
        } catch (Exception $e) {
            return to_route('productos')->with('error', 'Error al actualizar la imagen: ' . $e->getMessage());
        }
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

            return to_route('productos')->with('success', 'Producto actualizado exitosamente!!');
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
            'categorias.nombre as nombre_categoria',
            'proveedores.nombre as nombre_proveedor',
            'imagenes.ruta as imagen_producto',
            'imagenes.id as imagen_id' 
        )
        ->join('categorias', 'productos.categoria_id', '=' , 'categorias.id')
        ->join('proveedores', 'productos.proveedor_id', '=' , 'proveedores.id')
        ->leftJoin('imagenes', 'productos.id', '=', 'imagenes.producto_id')
        ->get();


        return view('modules.productos.tbody', compact('items'));
    }

    public function subirImagen($request, $id_producto)
    {
        $rutaImagen = $request->file('imagen')->store('imagenes', 'public');
        $nombreImagen = basename($rutaImagen);

        $item = new Imagen();
        $item->producto_id = $id_producto;
        $item->nombre = $nombreImagen;
        $item->ruta = $rutaImagen;

        return $item->save();
    }
}
