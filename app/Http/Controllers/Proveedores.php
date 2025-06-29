<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class Proveedores extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $titulo = 'Proveedores';
        $items = \App\Models\Proveedor::all();
        return view('modules.proveedores.index', compact('titulo', 'items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        $titulo = 'Crear Proveedor';
        return view('modules.proveedores.create', compact('titulo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            $item = new Proveedor();
            $item->nombre = $request->input('nombre');
            $item->telefono = $request->input('telefono');
            $item->postal = $request->input('postal');
            $item->email = $request->input('email');
            
            $item->sitioweb = $request->input('sitioweb');
            $item->notas = $request->input('notas');
            $item->save();

            return redirect()->route('proveedores')->with('success', 'Proveedor creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al crear el proveedor: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $proveedor = Proveedor::findOrFail($id);
        $titulo = 'Proveedor: ' . $proveedor->nombre;
        return view('modules.proveedores.show', compact('titulo', 'proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $proveedor = Proveedor::findOrFail($id);
        $titulo = 'Proveedor: ' . $proveedor->nombre;
        return view('modules.proveedores.edit', compact('titulo', 'proveedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //}
        try {
            $item = Proveedor::findOrFail($id);
            $item->nombre = $request->input('nombre');
            $item->telefono = $request->input('telefono');
            $item->postal = $request->input('postal');
            $item->email = $request->input('email');
            $item->sitioweb = $request->input('sitioweb');
            $item->notas = $request->input('notas');
            $item->save();

            return redirect()->route('proveedores')->with('success', 'Proveedor actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al actualizar el proveedor: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            $item = Proveedor::findOrFail($id);
            $item->delete();
            return redirect()->route('proveedores')->with('success', 'Proveedor eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al eliminar el proveedor: ' . $e->getMessage()]);
        }
    }
}
