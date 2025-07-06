<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Detalle_venta;
use App\Models\Venta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Hash;

class Ventas extends Controller
{
    //
    public function index()
    {
        $titulo = 'Titulo de Ventas';
        $items = Producto::select(
            'productos.*',
            'categorias.nombre as nombre_categoria',
            'proveedores.nombre as nombre_proveedor',
            'imagenes.ruta as imagen_producto',
            'imagenes.id as imagen_id'
        )
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->join('proveedores', 'productos.proveedor_id', '=', 'proveedores.id')
            ->leftJoin('imagenes', 'productos.id', '=', 'imagenes.producto_id')
            ->paginate(8);

        return view('modules.ventas.index', compact('titulo', 'items'));
    }


    public function contador_carrito()
    {
        $items = Session::get('items_carrito', []);
        $total = array_sum(array_column($items, 'cantidad'));
        return response()->json(['total' => $total]);
    }

    public function agregar_carrito(Request $request, $id_producto)
    {
        $item = Producto::select(
            'productos.*',
            'imagenes.ruta as imagen_producto'
        )
            ->leftJoin('imagenes', 'productos.id', '=', 'imagenes.producto_id')
            ->where('productos.id', $id_producto)
            ->firstOrFail();

        $cantidad_disponible = $item->cantidad;
        $cantidad_a_agregar = max((int) $request->input('cantidad', 1), 1); // Valor mínimo: 1

        $items_carrito = Session::get('items_carrito', []);
        $existe = false;

        foreach ($items_carrito as $key => $prod) {
            if ($prod['id'] == $id_producto) {
                $nueva_cantidad = $prod['cantidad'] + $cantidad_a_agregar;

                if ($nueva_cantidad > $cantidad_disponible) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No hay stock suficiente. Disponible: ' . $cantidad_disponible
                    ], 409);
                }

                $items_carrito[$key]['cantidad'] = $nueva_cantidad;
                $existe = true;
                break;
            }
        }

        if (!$existe) {
            if ($cantidad_a_agregar > $cantidad_disponible) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay stock suficiente. Disponible: ' . $cantidad_disponible
                ], 409);
            }

            $items_carrito[] = [
                'id'       => $item->id,
                'codigo'   => $item->codigo,
                'nombre'   => $item->nombre,
                'cantidad' => $cantidad_a_agregar,
                'precio'   => $item->precio_venta,
                'imagen'   => $item->imagen_producto ?? 'default.jpg',
            ];
        }

        Session::put('items_carrito', $items_carrito);

        if ($request->ajax()) {
            $totalItems = array_sum(array_column($items_carrito, 'cantidad'));
            return response()->json([
                'success' => true,
                'total'   => $totalItems,
            ]);
        }

        return to_route('ventas-nueva');
    }




    public function quitar_carrito($id_producto)
    {
        $items_carrito = Session::get('items_carrito', []);

        foreach ($items_carrito as $key => $carrito) {
            if ($carrito['id'] == $id_producto) {
                if ($carrito['cantidad'] > 1) {
                    $items_carrito[$key]['cantidad'] -= 1;
                } else {
                    unset($items_carrito[$key]);
                }
                break;
            }
        }


        $items_carrito = array_values($items_carrito);

        Session::put('items_carrito', $items_carrito);

        $total = array_sum(array_column($items_carrito, 'cantidad'));


        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado o cantidad reducida',
                'total' => $total,
            ]);
        }


        return to_route('ventas-nueva');
    }


    public function borrar_carrito()
    {
        Session::forget('items_carrito');
        return to_route('ventas-nueva');
    }

    public function mostrar_carrito()
    {
        $items = Session::get('items_carrito', []);
        $total = 0;

        // Calculamos subtotal por ítem
        $list = array_map(function ($prod) use (&$total) {
            $subtotal = $prod['precio'] * $prod['cantidad'];
            $total += $subtotal;
            return [
                'id'       => $prod['id'],
                'codigo'   => $prod['codigo'],
                'nombre'   => $prod['nombre'],
                'cantidad' => $prod['cantidad'],
                'precio'   => $prod['precio'],
                'subtotal' => $subtotal,
                'imagen'   => $prod['imagen'] ?? 'default.jpg', // ← Añadido aquí
            ];
        }, $items);

        return response()->json([
            'items' => $list,
            'total' => $total,
        ]);
    }




    public function vender(Request $request)
    {
        $items_carrito = $request->input('productos', []);

        // Verificación básica del array recibido
        if (empty($items_carrito) || !is_array($items_carrito)) {
            return response()->json([
                'success'       => false,
                'message'       => '¡El carrito está vacío o malformado!',
                'data_recibida' => $items_carrito
            ], 400);
        }

        // Validar estructura de cada producto
        foreach ($items_carrito as $index => $item) {
            if (
                !isset($item['id'], $item['cantidad'], $item['precio']) ||
                !is_numeric($item['id']) ||
                !is_numeric($item['cantidad']) ||
                !is_numeric($item['precio']) ||
                $item['cantidad'] <= 0 ||
                $item['precio'] < 0
            ) {
                return response()->json([
                    'success'         => false,
                    'message'         => "Datos incompletos o inválidos en el producto #$index",
                    'producto_erroneo'=> $item,
                    'data_recibida'   => $items_carrito
                ], 422);
            }
        }

        DB::beginTransaction();

        try {
            // Calcula total
            $totalVenta = array_reduce($items_carrito, function ($carry, $item) {
                return $carry + ($item['cantidad'] * $item['precio']);
            }, 0);

            // Crea venta principal
            $venta = new Venta();
            $venta->user_id     = Auth::id();
            $venta->total_venta = $totalVenta;
            $venta->save();

            // Detalles y stock
            foreach ($items_carrito as $item) {
                $producto = Producto::find($item['id']);
                if (!$producto) {
                    DB::rollBack();
                    return response()->json([
                        'success'       => false,
                        'message'       => 'Producto no encontrado: ID ' . $item['id'],
                        'data_recibida' => $items_carrito
                    ], 404);
                }
                if ($producto->cantidad < $item['cantidad']) {
                    DB::rollBack();
                    return response()->json([
                        'success'        => false,
                        'message'        => 'Stock insuficiente para: ' . $producto->nombre,
                        'producto'       => $item,
                        'stock_disponible'=> $producto->cantidad,
                        'data_recibida'  => $items_carrito
                    ], 400);
                }

                $detalle = new Detalle_venta();
                $detalle->venta_id        = $venta->id;
                $detalle->producto_id     = $producto->id;
                $detalle->cantidad        = $item['cantidad'];
                $detalle->precio_unitario = $item['precio'];
                $detalle->sub_total       = $item['cantidad'] * $item['precio'];
                $detalle->save();

                $producto->cantidad -= $item['cantidad'];
                $producto->save();
            }

            DB::commit();

            // 🔥 **Aquí limpiamos el carrito de la sesión para que quede vacío**
            Session::forget('items_carrito');

            return response()->json([
                'success'       => true,
                'message'       => '¡Venta realizada con éxito!',
                'data_recibida' => $items_carrito,
                'venta_id'      => $venta->id,
                'total'         => $totalVenta
            ]);
        }
        catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error al procesar venta: ' . $th->getMessage());
            return response()->json([
                'success'       => false,
                'message'       => 'Error inesperado al procesar la venta.',
                'error'         => $th->getMessage(),
                'data_recibida' => $items_carrito
            ], 500);
        }
    }
}
