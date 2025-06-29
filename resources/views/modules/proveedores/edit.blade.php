@extends('layouts.main')
@section('titulo', $titulo)
@section('contenido')

    <main id="main" class"main">

        <div class="pagetitle">
            <h1>Proveedor => {{$proveedor->nombre}}</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">proveedores</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Editar proveedor</h5>

                            <form action="{{ route('proveedores-update', $proveedor->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                               <label for="nombre">Nombre de Proveedor</label>
                                <input type="text" class="form-control" required name="nombre" id="nombre" value="{{$proveedor->nombre}}">
                                <label for="telefono">Telefono</label>
                                <input type="text" class="form-control" required name="telefono" id="telefono" value="{{$proveedor->telefono}}">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" required name="email" id="email" value="{{$proveedor->email}}">
                                <label for="postal">Postal</label>
                                <input type="text" class="form-control" required name="postal" id="postal" value="{{$proveedor->postal}}">
                                <label for="sitioweb">Sitio Web</label>
                                <input type="text" class="form-control" required name="sitioweb" id="sitioweb" value="{{$proveedor->sitioweb}}">
                                <label for="notas">Notas</label>
                               <textarea name="notas" id="notas" cols="30" rows="10" class="form-control">{{ $proveedor->notas }}</textarea>
                                
                                <button class="btn btn-primary mt-3">Editar</button>
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
