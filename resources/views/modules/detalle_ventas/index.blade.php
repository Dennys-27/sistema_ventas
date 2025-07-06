@extends('layouts.main')
@section('titulo', $titulo)
@section('contenido')

    <main id="main" class"main">

        <div class="pagetitle">
            <h1>Detalle Venta</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Detalle Venta</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Administrar Detalle Venta</h5>
                            <p>
                                Admnistrar las Venta de nuestros productos.
                            </p>

                            <hr>
                            <!-- Table with stripped rows -->
                            <table class="table table-border datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center">Total de Venta</th>
                                        <th class="text-center">Fecha Venta</th>
                                        <th class="text-center">Usuario</th>
                                        <th class="text-center">Ver Detalle</th>
                                        <th class="text-center">Imprimir</th>
                                        <th class="text-center">Revocar Venta</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach ($items as $item)
                                        <tr>
                                            <td class="text-center">{{ $item->total_venta }}</td>
                                            <td class="text-center">{{ $item->created_at }}</td>
                                            <td class="text-center">{{ $item->nombre_usuario }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('detalle-venta.detalle', $item->id) }}"
                                                    class="btn btn-outline-success btn-sm" title="Ver Detalle">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('detalle-venta.ticket', $item->id) }}"
                                                    class="btn btn-outline-primary btn-sm" title="Imprimir Ticket">
                                                    <i class="fa-solid fa-print me-1"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('detalle-revocar', $item->id) }}" method="POST"
                                                    onsubmit="return confirm('¿¿Esta seguro de revocar la venta??')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger">Revocar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach


                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->


                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main><!-- End #main -->
@endsection
