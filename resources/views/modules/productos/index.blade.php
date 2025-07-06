@extends('layouts.main')
@section('titulo', $titulo)
@section('contenido')

    <main id="main" class"main">

        <div class="pagetitle">
            <h1>Productos</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Productos</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Administrar Productos</h5>
                            <p>
                                Admnistrar las Productos de nuestros productos.
                            </p>
                            <hr>

                            <!-- Table with stripped rows -->
                            <a href="{{ route('productos-create') }}" class="btn btn-primary">
                                <i class="fa-solid fa-circle-plus"></i> Agregar nueva producto
                            </a>
                            <hr>
                            <div class="table-responsive">

                                <!-- Table with stripped rows -->
                                <table class="table table-border datatable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Categoria</th>
                                            <th class="text-center">Proveedor</th>
                                            <th class="text-center">Codigo</th>
                                            <th class="text-center">Nombre</th>
                                            <th class="text-center">Imagen</th>
                                            <th class="text-center">Descripcion</th>
                                            <th class="text-center">Cantidad</th>
                                            <th class="text-center">Venta</th>
                                            <th class="text-center">Compra</th>
                                            <th class="text-center">Activo</th>
                                            <th class="text-center">Comprar</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        @include('modules.productos.tbody')

                                        <!-- Puedes agregar más filas aquí dinámicamente -->
                                    </tbody>
                                </table>
                                <!-- End Table with stripped rows -->


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </section>

    </main><!-- End #main -->
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.form-check-input').on("change", function() {
                let id = $(this).attr('id');
                let estado = $(this).is(':checked') ? 1 : 0;
                cambiar_estado(id, estado);
            });
        });

        function cambiar_estado(id, estado) {

        }

        function cambiar_estado(id, estado) {
            $.ajax({
                type: "GET",
                url: "productos/cambiar/" + id + "/" + estado,
                success: function(respuesta) {
                    if (respuesta == 1) {
                        Swal.fire({
                            title: 'Exito!',
                            text: 'Cambio de estado exitoso!',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });
                        recargar_tbody();
                    } else {
                        Swal.fire({
                            title: 'Fallo!',
                            text: 'No se llevo a cabo el cambio!',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                }
            })
        }

        function recargar_tbody() {
            $.ajax({
                url: '{{ route('productos-tbody') }}',
                type: 'GET',
                success: function(data) {
                    $('#tbody-productos').html(data);
                },
                error: function(xhr, status, error) {
                    console.error('Error al cargar el tbody:', error);
                }
            });
        }
    </script>
@endpush
