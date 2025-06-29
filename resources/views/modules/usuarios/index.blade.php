@extends('layouts.main')
@section('titulo', $titulo)
@section('contenido')

    <main id="main" class"main">

        <div class="pagetitle">
            <h1>Usuarios</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">Usuarios</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Administrar Usuarios</h5>
                            <p>
                                Administrar los usuarios de la aplicacion.
                            </p>
                            <!-- Table with stripped rows -->
                            <a href="{{ route('usuarios-create') }}" class="btn btn-primary">
                                <i class="fa-solid fa-circle-plus"></i> Agregar nueva Usuario
                            </a>
                            <hr>
                            <!-- Table with stripped rows -->
                            <table class="table table-border datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nombre</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Rol</th>
                                        <th class="text-center">Cambio Password</th>
                                        <th class="text-center">Activo</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center" id="tbody-usuarios">
                                    @include('modules.usuarios.tbody')

                                    <!-- Puedes agregar más filas aquí dinámicamente -->
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
    @include('modules.usuarios.cambiar_password')
    @push('scripts')
        <script>
            function recargar_tbody() {
                $.ajax({
                    url: '{{ route('usuarios-tbody') }}',
                    type: 'GET',
                    success: function(data) {
                        $('#tbody-usuarios').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al cargar el tbody:', error);
                    }
                });
            }

            function cambiar_estado(id, estado) {
                $.ajax({
                    type: "GET",
                    url: "usuarios/activar/" + id + "/" + estado,
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

            function agregar_id_usuario(id) {
                $('#id_usuario').val(id);
            }

            function cambiar_password() {
                let id = $('#id_usuario').val();
                let password = $('#password').val();

                $.ajax({
                    type: "GET",
                    url: "usuarios/cambiar-password/" + id + "/" + password,

                    success: function(respuesta) {
                        if (respuesta == 1) {
                            Swal.fire({
                                title: 'Exito!',
                                text: 'Cambio de password exitoso!',
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            });
                            $('#frmPassword')[0].reset();
                        } else {
                            Swal.fire({
                                title: 'Fallo!',
                                text: 'Cambio de password no exitoso!',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al cambiar el password:', error);
                    }
                })
                return false;
            }

            $(document).ready(function() {
                $('.form-check-input').on("change", function() {
                    let id = $(this).attr('id').replace('activo', '');
                    let activo = $(this).is(':checked') ? 1 : 0;
                    console.log(id, activo);
                    cambiar_estado(id, activo);

                })
            });
        </script>
    @endpush
