@extends('layouts.main')
@section('titulo', $titulo)
@section('contenido')

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Editar Producto</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Productos</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-12">

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title">Editar nuevo producto</h5>

                        <form action="{{ route('productos-update', $item->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="categoria_id" class="form-label">Categoría</label>
                                    <select name="categoria_id" id="categoria_id" class="form-select" required>
                                        <option value="">Selecciona una categoría</option>
                                        @foreach ($categorias as $categoria)
                                            @if ($categoria->id == $item->categoria_id)
                                                <option selected value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                            @else
                                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>   
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="proveedor_id" class="form-label">Proveedor</label>
                                    <select name="proveedor_id" id="proveedor_id" class="form-select" required>
                                        <option value="">Selecciona un proveedor</option>
                                        @foreach ($proveedores as $proveedor)
                                            @if ($proveedor->id == $item->proveedor_id)
                                                <option selected value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                                            @else   
                                                <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                                            @endif
                                            <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label">Nombre del Producto</label>
                                    <input type="text" class="form-control" name="nombre" id="nombre" value="{{ $item->nombre }}" >
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="codigo" class="form-label">Código</label>
                                    <input type="text" class="form-control" name="codigo" id="codigo" value="{{ $item->codigo }}">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control" name="descripcion" id="descripcion" rows="3" placeholder="Escribe una descripción...">{{ $item->descripcion }}</textarea>
                                </div>
                                 <div class="col-md-6 mb-3">
                                    <label for="precio_venta" class="form-label">Precio de Venta</label>
                                    <input type="number" class="form-control" name="precio_venta" id="precio_venta" value="{{ $item->precio_venta }}">
                                </div>
                            </div>

                            <div class="mt-4 d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-floppy-disk"></i> Guardar
                                </button>
                                <a href="{{ route('productos') }}" class="btn btn-secondary">
                                    Cancelar
                                </a>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>

</main>
@endsection
