@extends('layouts.main')
@section('titulo', $titulo)
@section('contenido')

    <main id="main" class"main">

        <div class="pagetitle">
            <h1>Usuario => {{ $usuario->name }}</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">Usuario</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Editar Usuario</h5>

                            <form action="{{ route('usuarios-update', $usuario->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <label for="name">Nombre de usuario</label>
                                <input type="text" class="form-control" required name="name" id="name"
                                    value="{{ $usuario->name }}">

                                <label for="email">Correo</label>
                                <input type="email" class="form-control" required name="email" id="email"
                                    value="{{ $usuario->email }}">

                                <label for="rol">Rol de usuario</label>
                                <select name="rol" id="rol" class="form-select">
                                    <option value="">Selecciona el rol</option>
                                    @if ($usuario->rol == 'admin')
                                        <option value="admin" selected>Admin</option>
                                        <option value="cajero">Cajero</option>
                                    @else
                                        <option value="admin">Admin</option>
                                        <option value="cajero" selected>Cajero</option>
                                    @endif
                                </select>


                                <button class="btn btn-primary mt-3">Editar</button>
                                <a href="{{ route('usuarios') }}" class="btn btn-info mt-3">
                                    Cancelar
                                </a>
                            </form>



                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main><!-- End #main -->
