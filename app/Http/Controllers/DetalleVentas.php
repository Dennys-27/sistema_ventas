<?php

namespace App\Http\Controllers;

use App\Models\Detalle_venta;
use App\Models\Producto;
use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetalleVentas extends Controller
{
    //
    public function index()
    {
        $titulo = 'Detalle de Ventas';
        $items = Venta::select(
            'ventas.*',
            'users.name as nombre_usuario'
        )
            ->join('users', 'ventas.user_id', '=', 'users.id')
            ->orderBy('ventas.created_at', 'desc')
            ->get();
        return view('modules.detalle_ventas.index', compact('titulo', 'items'));
    }

    public function detalle_venta($id)
    {
        $titulo = 'Detalle de Venta';
        $venta =  Venta::select(
            'ventas.*',
            'users.name as nombre_usuario'
        )
            ->join('users', 'ventas.user_id', '=', 'users.id')
            ->where('ventas.id', $id)
            ->firstOrFail();

        $detalles = Detalle_venta::select(
            'detalle_venta.*',
            'productos.nombre as nombre_producto'
        )
            ->join('productos', 'detalle_venta.producto_id', '=', 'productos.id')
            ->where('venta_id', $id)
            ->get();

        return view('modules.detalle_ventas.detalle', compact('venta', 'detalles', 'titulo'));
    }

    public function revocar($id)
    {
        DB::beginTransaction();
        try {
            $detalles = Detalle_venta::select('producto_id', 'cantidad')
                ->where('venta_id', $id)
                ->get();

            foreach ($detalles as $detalle) {
               Producto::where('id', $detalle->producto_id)
                    ->increment('cantidad', $detalle->cantidad);
            }


            
            Detalle_venta::where('venta_id', $id)->delete();
            Venta::where('id', $id)->delete();

            DB::commit();
            return redirect()->route('detalle-venta')->with('success', 'Venta revocada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('detalle-venta')->with('error', 'Error al revocar la venta: ' . $e->getMessage());
        }
    }

    public function generarTicket($id)
    {
        $venta = Venta::select(
            'ventas.*',
            'users.name as nombre_usuario'
        )
            ->join('users', 'ventas.user_id', '=', 'users.id')
            ->where('ventas.id', $id)
            ->firstOrFail();

        $detalles = Detalle_venta::select(
            'detalle_venta.*',
            'productos.nombre as nombre_producto'
        )
            ->join('productos', 'detalle_venta.producto_id', '=', 'productos.id')
            ->where('venta_id', $id)
            ->get();

        $pdf = Pdf::loadView('modules.detalle_ventas.ticket', compact('venta', 'detalles'));
        return $pdf->stream('ticket_venta_' . $id . '.pdf');
    }
}
