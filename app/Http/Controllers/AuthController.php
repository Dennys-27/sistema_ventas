<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function index(){
         $titulo = "Login de usuarios";
        return view("modules.auth.login", compact("titulo"));
    }

    public function logear(Request $request){
        //validar datos
        $credenciales = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //verifica si existe un solo email
        $user = User::where('email', $request->email)->first();

        //si no existe el usuario
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'El correo electrónico no está registrado.']);
        }

        //el usuario este activo
        if(!$user->activo){
            return back()->withErrors(['email' => 'La cuenta esta inactiva.']);
        }

        //crear la sesion de usuario
        Auth::login($user);
        $request->session()->regenerate();

        return to_route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function crearAdmin(){
        //crear un administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'activo' => true,
            'rol' => 'admin',
        ]);

        return "Administrador creado con éxito.";
    }
}
