@extends('layouts.main')
@section('titulo', $titulo)
@section('contenido')

    <main id="main" class"main">

        <div class="pagetitle">
            <h1>Agregar Proveedor</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Proveedores</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Agregar nueva Proveedor</h5>

                            <form action="{{ route('proveedores-store') }}" method="POST">
                                @csrf
                                <label for="nombre">Nombre de Proveedor</label>
                                <input type="text" class="form-control" required name="nombre" id="nombre">
                                <label for="telefono">Telefono</label>
                                <input type="text" class="form-control" required name="telefono" id="telefono">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" required name="email" id="email">
                                <label for="postal">Postal</label>
                                <input type="text" class="form-control" required name="postal" id="postal">
                                <label for="sitioweb">Sitio Web</label>
                                <input type="text" class="form-control" required name="sitioweb" id="sitioweb">
                                <label for="notas">Notas</label>
                                <textarea class="form-control" id="notas" name="notas" rows="5" placeholder="Escribe tus notas aquí..."></textarea>
                                <button class="btn btn-primary mt-3">Guardar</button>
                                <a href="{{ route('proveedores') }}" class="btn btn-info mt-3">
                                    Cancelar
                                </a>
                            </form>



                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main><!-- End #main -->
