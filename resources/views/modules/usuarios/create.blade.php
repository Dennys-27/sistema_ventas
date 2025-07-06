@extends('layouts.main')
@section('titulo', $titulo)
@section('contenido')

    <main id="main" class"main">

        <div class="pagetitle">
            <h1>Agregar Usuario</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Usuario</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Agregar nueva usuario</h5>

                            <form action="{{ route('usuarios-store') }}" method="POST">
                                @csrf
                                <label for="nombre">Nombre de usuario</label>
                                <input type="text" class="form-control" required name="name" id="name">
                                <label for="email">Correo</label>
                                <input type="email" class="form-control" required name="email" id="email">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" required name="password" id="password">
                                <label for="rol">Rol de Usuario</label>
                                <select name="rol" id="rol" class="form-select">
                                    <option value="admin">Selecciona el Rol</option>
                                    <option value="admin">Administrador</option>
                                    <option value="cajero">Vendedor</option>
                                </select>
                                <button class="btn btn-primary mt-3">Guardar</button>
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
