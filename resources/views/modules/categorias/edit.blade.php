@extends('layouts.main')
@section('titulo', $titulo)
@section('contenido')

    <main id="main" class"main">

        <div class="pagetitle">
            <h1>Categoria => {{$categoria->nombre}}</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Categorias</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Editar Categoria</h5>

                            <form action="{{ route('categorias-update', $categoria->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <label for="nombre">Nombre de categoria</label>
                                <input type="text" class="form-control" required name="nombre" id="nombre" value="{{ $categoria->nombre }}">
                                <button class="btn btn-primary mt-3">Editar</button>
                                <a href="{{ route('categorias') }}" class="btn btn-info mt-3">
                                    Cancelar
                                </a>
                            </form>



                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main><!-- End #main -->
