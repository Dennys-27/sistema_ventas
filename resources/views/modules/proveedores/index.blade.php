@extends('layouts.main')
@section('titulo', $titulo)
@section('contenido')

    <main id="main" class"main">

        <div class="pagetitle">
            <h1>Proveedores</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">Proveedores</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Administrar Proveedores</h5>
                            <p>
                                Admnistrar las Proveedores de nuestros productos.
                            </p>
                            <!-- Table with stripped rows -->
                            <a href="{{ route('proveedores-create') }}" class="btn btn-primary">
                                <i class="fa-solid fa-circle-plus"></i> Agregar nueva proveedor
                            </a>
                            <hr>
                            <!-- Table with stripped rows -->
                            <table class="table table-border datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nombre</th>
                                        <th class="text-center">telenofo</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Postal</th>
                                        <th class="text-center">Sitio web</th>
                                        <th class="text-center">Nota</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>{{ $item->nombre }}</td>
                                            <td>{{ $item->telefono }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->postal }}</td>
                                            <td>{{ $item->sitioweb }}</td>
                                            <td>{{ $item->notas }}</td>

                                            <td><a href="{{ route('proveedores-edit', $item->id) }}"
                                                    class="btn btn-warning btn-sm"><i
                                                        class="fa-solid fa-pen-to-square"></i></a>
                                                <a href="{{ route('proveedores-show', $item->id) }}"
                                                    class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach

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
