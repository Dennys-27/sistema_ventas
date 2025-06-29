<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Hash;

class Usuarios extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $titulo = 'Administrar Usuarios';
        $items = User::all();
        return view('modules.usuarios.index', compact('items', 'titulo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $titulo = 'Crear Usuario';
        return view('modules.usuarios.create', compact('titulo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'activo' => true,
                'rol' => $request->rol
            ]);

            return to_route('usuarios')->with('success', 'Usuario creado exitosamente.');
        } catch (Exception $e) {
            return to_route('usuarios')->with('error', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $usuario = User::find($id);
        $titulo = 'Editar Usuario';
        return view('modules.usuarios.edit', compact('titulo', 'usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

        try {
            $usuario = User::find($id);
            $usuario->name = $request->name;
            $usuario->email = $request->email;
            $usuario->rol = $request->rol;
            $usuario->save();
            return to_route('usuarios')->with('success', 'Usuario actualizado exitosamente.');
        } catch (Exception $e) {
            return to_route('usuarios')->with('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function tbody()
    {
        //
        $items = User::all();
        return view('modules.usuarios.tbody', compact('items'));
    }

    public function activar($id, $estado)
    {
        //
        $usuario = User::find($id);
        $usuario->activo = $estado;

        return $usuario->save();
    }


    public function cambio_password($id, $password)
    {
        $item = User::find($id);
        $item->password = Hash::make($password);
        return $item->save();
    }
}
