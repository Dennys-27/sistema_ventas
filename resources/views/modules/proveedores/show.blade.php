@extends('layouts.main')
@section('titulo', $titulo)
@section('contenido')

    <main id="main" class"main">

        <div class="pagetitle">
            <h1>Proveedor => {{$proveedor->nombre}}</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Proveedor</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">¿Estas seguro de eliminar este proveedor?</h5>

                            <form action="{{ route('proveedores-destroy', $proveedor->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <label for="nombre">Nombre de proveedor</label>
                                <input type="text" class="form-control" readonly name="nombre" id="nombre" value="{{$proveedor->nombre}}">
                                <button class="btn btn-danger mt-3">Eliminar</button>
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
