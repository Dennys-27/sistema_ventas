<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class Categorias extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $titulo = 'Administrar Categorias';
        $items = Categoria::all(); // Assuming you have a Categoria model
        return view('modules.categorias.index', compact('titulo', 'items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $titulo = 'Crear Categorias';
        return view('modules.categorias.create', compact('titulo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            $item = new Categoria();
            $item->nombre = $request->input('nombre');
            $item->user_id = Auth::user()->id; 
            $item->save();
            return to_route('categorias')->with('success', 'Categoria creada exitosamente.'); 
        } catch (Exception $e) {
            return to_route('categorias')->with('error', 'Error al crear la categoria: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $categoria = Categoria::find($id);
        $titulo = 'Eliminar Categorias';

       

        return view('modules.categorias.show', compact('categoria', 'titulo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $categoria = Categoria::find($id);
        $titulo = 'Editar Categoria';

       

        return view('modules.categorias.edit', compact('categoria', 'titulo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
       try {
            $item = Categoria::find($id);
            $item->nombre = $request->input('nombre');
            $item->user_id = Auth::user()->id; 
            $item->save();
            return to_route('categorias')->with('success', 'Categoria actualizada exitosamente.');
        } catch (Exception $e) {
            return to_route('categorias')->with('error', 'Error al actualizar la categoria: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try{
            $item = Categoria::find($id);
                
            $item->delete();
            return to_route('categorias')->with('success', 'Categoria eliminada exitosamente.');
        }catch (Exception $e) {
            return to_route('categorias')->with('error', 'Error al eliminar la categoria: ' . $e->getMessage());
        }
    }   
}
