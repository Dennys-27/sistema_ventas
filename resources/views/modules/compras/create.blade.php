@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main">
  <div class="pagetitle mb-4">
    <h1 class="fw-bold text-primary"><i class="bi bi-cart-plus-fill me-2"></i>Hacer una compra</h1>
  </div>

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card shadow rounded-4">
          <div class="card-body p-4">
            <h5 class="card-title text-secondary mb-4">
              Compra nueva de: <span class="text-dark fw-semibold">{{ $item->nombre }}</span>
            </h5>

            <form action="{{ route('compras-store') }}" method="POST">
              @csrf
              <input type="hidden" value="{{ $item->id }}" name="id">

              <div class="mb-3">
                <label for="cantidad" class="form-label fw-semibold">Cantidad del producto</label>
                <input type="number" class="form-control border-primary shadow-sm" required name="cantidad" id="cantidad" min="1">
              </div>

              <div class="mb-4">
                <label for="precio_compra" class="form-label fw-semibold">Precio de compra</label>
                <input type="number" id="precio_compra" name="precio_compra" class="form-control border-success shadow-sm" required step="0.01">
              </div>

              <div class="d-flex justify-content-between">
                <button class="btn btn-success px-4">
                  <i class="bi bi-check-circle me-1"></i> Comprar
                </button>
                <a href="{{ route('productos') }}" class="btn btn-outline-secondary px-4">
                  <i class="bi bi-x-circle me-1"></i> Cancelar
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
