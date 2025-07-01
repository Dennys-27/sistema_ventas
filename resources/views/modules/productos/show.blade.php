@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Eliminar Producto</h1>
    
  </div><!-- End Page Title -->
  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Eliminar producto del stock</h5>
            <p>
              Una vez que el producto sea eliminado, no podra ser recuperado!!!!
            </p>
            
            <!-- Table with stripped rows -->
            
            <table class="table">
              <thead>
                <tr>
                  <th class="text-center">Categoria</th>
                  <th class="text-center">Proveedor</th>
                  <th class="text-center">Nombre</th>
                  <th class="text-center">Imagen</th>
                  <th class="text-center">Descripcion</th>
                  <th class="text-center">Cantidad</th>
                  <th class="text-center">Venta</th>
                  <th class="text-center">Compra</th>
                  <th class="text-center">Activo</th>
                  
                </tr>
              </thead>
              <tbody>
                 
                  <tr class="text-center">
                    <td>{{ $item->categoria_nombre}} </td>
                    <td>{{ $item->proveedor_nombre }}</td>
                    <td>{{ $item->nombre }}</td>
                    <td></td>
                    <td>{{ $item->descripcion }}</td>
                    <td>{{ $item->cantidad }}</td>
                    <td>{{ $item->precio_compra }}</td>
                    <td>{{ $item->precio_venta }}</td>
                    <td>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="{{ $item->id }}" 
                            {{ $item->activo ? 'checked' : '' }}  >
                        </div>
                    </td>
                  </tr>
                 
              </tbody>
            </table>
            <!-- End Table with stripped rows -->
            <hr>
            <form action="{{ route('productos-destroy', $item->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Eliminar producto</button>
                <a href="{{ route('productos') }}" class="btn btn-info">Cancelar</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>
@endsection