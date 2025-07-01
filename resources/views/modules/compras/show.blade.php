@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Eliminar compra de producto</h1>
    
  </div><!-- End Page Title -->
  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Eliminar compra</h5>
            <p>
              Una vez eliminada la compra, no podra ser recuperada!!
            </p>
            
            <table class="table ">
              <thead>
                <tr>
                  <th class="text-center">Usuario</th>
                  <th class="text-center">Producto</th>
                  <th class="text-center">Cantidad</th>
                  <th class="text-center">Precio de compra</th>
                  <th class="text-center">Total compra</th>
                  <th class="text-center">Fecha</th>
                  
                </tr>
              </thead>
              <tbody>
                 
                  <tr class="text-center">
                    <td>{{ $item->nombre_usuario }}</td>
                    <td>{{ $item->nombre_producto }}</td>
                    <td>{{ $item->cantidad }}</td>
                    <td>${{ $item->precio_compra }}</td>
                    <td>${{ $item->precio_compra * $item->cantidad }}</td>
                    <td>{{ $item->created_at }}</td>
                  </tr>
                  
              </tbody>
            </table>
            <!-- End Table with stripped rows -->
            <hr>
            <form action="{{ route('compras-destroy', $item->id) }}" method="post">
                @csrf
                @method('DELETE')
                <input type="text" value="{{ $item->producto_id }}" name="producto_id" hidden>
                <button class="btn btn-danger mt-3">Eliminar Compra</button>
                <a href="{{ route("compras") }}" class="btn btn-info mt-3">
                    Cancelar
                </a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>
@endsection