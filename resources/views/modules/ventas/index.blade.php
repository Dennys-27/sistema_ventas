@extends('layouts.main')
@section('titulo', $titulo)
@section('contenido')

    <main id="main" class"main">

        <div class="pagetitle">
            <h1>Ventas</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Ventas</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Crear una Nueva Venta</h5>
                            <p>
                                Crear ventas de los productos existentes
                            </p>
                            <div class="d-flex justify-content-end align-items-center mb-3">
                                <button type="button" class="btn btn-success position-relative">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    <span class="ms-1">Carrito</span>
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                        id="contador-carrito">
                                        0
                                    </span>
                                </button>
                            </div>
                            <hr>

                            <div class="row" id="lista-productos">
                                @foreach ($items as $item)
                                    <div class="col-md-3 mb-4">
                                        <div class="card h-100">
                                            <img src="{{ asset('storage/' . $item->imagen_producto) }}" class="card-img-top"
                                                alt="{{ $item->nombre }}">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $item->nombre }}</h5>
                                                <p class="card-text mb-1">Código: <span
                                                        class="text-muted">{{ $item->codigo }}</span></p>
                                                <p class="card-text mb-2">Precio:
                                                    ${{ number_format($item->precio_venta, 2) }}</p>

                                                <div class="input-group input-group-sm mb-2" style="width: 120px;">
                                                    <button class="btn btn-outline-secondary btn-restar"
                                                        type="button">–</button>
                                                    <input type="text" class="form-control text-center cantidad-input"
                                                        id="cantidad_{{ $item->id }}" value="1" readonly>
                                                    <button class="btn btn-outline-secondary btn-sumar"
                                                        type="button">+</button>
                                                </div>

                                                <button class="btn btn-sm btn-primary agregar-carrito"
                                                    data-id="{{ $item->id }}" data-nombre="{{ $item->nombre }}"
                                                    data-precio="{{ $item->precio_venta }}"
                                                    data-codigo="{{ $item->codigo }}"
                                                    data-imagen_producto="{{ $item->imagen_producto }}"
                                                    data-precio_venta="{{ $item->precio_venta }}">
                                                    <i class="fa-solid fa-cart-plus"></i> Agregar al carrito
                                                </button>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Paginación -->
                            {{ $items->links('vendor.pagination.default') }}




                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Modal Carrito -->

        <div class="modal fade" id="modalCarrito" tabindex="-1" aria-labelledby="modalCarritoLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCarritoLabel">Tu Carrito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body" id="contenido-carrito">
                        <p class="text-center">Cargando...</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <!-- El botón NO hace submit directo, tendrá un id para manejar con JS -->
                        <button id="btnRealizarVenta" class="btn btn-primary">Realizar Venta</button>
                        <!-- Token CSRF oculto para enviarlo en AJAX -->
                        <input type="hidden" id="csrf_token" value="{{ csrf_token() }}">
                    </div>
                </div>
            </div>
        </div>

    </main><!-- End #main -->
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            const btnCarrito = document.querySelector('#contador-carrito').closest('button');
            const modalEl = document.getElementById('modalCarrito');
            const contenido = document.getElementById('contenido-carrito');
            const modal = new bootstrap.Modal(modalEl);

            // ——— Función para mostrar spinner y cargar productos del carrito desde servidor
            function cargarCarrito() {
                contenido.innerHTML = `
                <div class="d-flex justify-content-center align-items-center" style="height: 150px;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            `;

                fetch('{{ route('ventas.mostrar.carrito') }}')
                    .then(res => {
                        if (!res.ok) throw new Error('Error al obtener el carrito');
                        return res.json();
                    })
                    .then(data => {
                        if (data.items.length === 0) {
                            contenido.innerHTML = `
                            <div class="text-center my-4">
                                <i class="fa-solid fa-cart-shopping fa-2x mb-2 text-muted"></i>
                                <p class="text-muted mb-0">Tu carrito está vacío.</p>
                            </div>
                        `;
                        } else {
                            let html = `
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Imagen</th>
                                            <th>Código</th>
                                            <th>Producto</th>
                                            <th class="text-center">Cantidad</th>
                                            <th class="text-end">Precio</th>
                                            <th class="text-end">Subtotal</th>
                                            <th class="text-end">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;

                            data.items.forEach(item => {
                                html += `
                                <tr>
                                    <td>
                                        <img src="/storage/${item.imagen}" alt="${item.nombre}" class="rounded shadow-sm" style="width: 45px; height: 45px; object-fit: cover;">
                                    </td>
                                    <td>${item.codigo}</td>
                                    <td>${item.nombre}</td>
                                    <td class="text-center">${item.cantidad}</td>
                                    <td class="text-end">$${item.precio.toFixed(2)}</td>
                                    <td class="text-end">$${item.subtotal.toFixed(2)}</td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-danger quitar-carrito" data-id="${item.id}" data-nombre="${item.nombre}" title="Quitar del carrito">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                            });

                            html += `
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="5" class="text-end"><strong>Total:</strong></td>
                                            <td class="text-end"><strong>$${data.total.toFixed(2)}</strong></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        `;

                            contenido.innerHTML = html;
                        }

                        modal.show();
                    })
                    .catch(() => {
                        contenido.innerHTML = `
                        <div class="alert alert-danger text-center">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i> No se pudo cargar el carrito.
                        </div>
                    `;
                        modal.show();
                    });
            }

            // ——— Abrir modal
            btnCarrito.addEventListener('click', cargarCarrito);


            // Botón para realizar venta (AJAX)
            $('#btnRealizarVenta').on('click', function() {
                const btn = $(this);
                btn.prop('disabled', true);
                const csrfToken = $('#csrf_token').val();

                const carrito = JSON.parse(localStorage.getItem('carrito')) || [];

                if (carrito.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Carrito vacío',
                        text: 'No hay productos en el carrito.',
                    });
                    btn.prop('disabled', false);
                    return;
                }
                console.log('🛒 Productos a enviar:', carrito);
                fetch('/ventas/vender/', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            productos: carrito
                        })
                    })
                    .then(res => {
                        btn.prop('disabled', false);
                        if (!res.ok) return res.json().then(err => Promise.reject(err));
                        return res.json();
                    })
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Venta realizada',
                            text: data.message || '¡Venta exitosa!',
                        });
                        modal.hide();

                        // Limpiar carrito local y contador
                        localStorage.removeItem('carrito');
                        $('#contador-carrito').text('0');
                    })
                    .catch(err => {
                        btn.prop('disabled', false);
                        console.error('❌ Error en la venta:', err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: err.message || 'No se pudo procesar la venta.',
                        });
                    });
            });
            $('#btnRealizarVenta').on('click', function() {
                const btn = $(this);
                btn.prop('disabled', true);
                const csrfToken = $('#csrf_token').val();

                const carrito = JSON.parse(localStorage.getItem('carrito')) || [];

                if (carrito.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Carrito vacío',
                        text: 'No hay productos en el carrito.',
                    });
                    btn.prop('disabled', false);
                    return;
                }

                console.log('🛒 Productos a enviar:', carrito);

                fetch('/ventas/vender/', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            productos: carrito
                        })
                    })
                    .then(res => {
                        btn.prop('disabled', false);
                        return res.json();
                    })
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message || 'Error inesperado al procesar la venta.');
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Venta realizada',
                            text: data.message || '¡Venta exitosa!',
                        });

                        localStorage.removeItem('carrito');
                        $('#contador-carrito').text('0');
                        modal.hide();
                    })
                    .catch(err => {
                        btn.prop('disabled', false);
                        console.error('❌ Error en la venta:', err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: err.message || 'No se pudo procesar la venta.',
                        });
                    });
            });


            // ——— Quitar producto del localStorage
            function quitarDelLocalStorage(id) {
                // Obtener el carrito desde localStorage o inicializarlo vacío
                let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

                // Buscar el producto en el carrito
                const producto = carrito.find(p => p.id === id);

                // Si el producto existe, restamos su cantidad
                if (producto) {
                    // Si la cantidad es mayor que 1, restamos 1
                    if (producto.cantidad > 1) {
                        producto.cantidad -= 1;
                    } else {
                        // Si la cantidad es 1, lo eliminamos
                        carrito = carrito.filter(p => p.id !== id);
                    }
                }

                // Guardamos los cambios en localStorage
                localStorage.setItem('carrito', JSON.stringify(carrito));

                // Actualizar el contador de productos en el carrito
                actualizarContadorDesdeLocalStorage();
            }


            // ——— Guardar en localStorage (agregar producto)
            function guardarEnLocalStorage(id, producto) {
                let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
                const index = carrito.findIndex(p => p.id === id);
                if (index !== -1) {
                    carrito[index].cantidad += producto.cantidad;
                } else {
                    carrito.push(producto);
                }
                localStorage.setItem('carrito', JSON.stringify(carrito));
                console.log('Producto guardado en localStorage:', producto);
                actualizarContadorDesdeLocalStorage();
            }

            // ——— Actualizar contador desde localStorage
            function actualizarContadorDesdeLocalStorage() {
                const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
                const total = carrito.reduce((acc, item) => acc + item.cantidad, 0);
                $('#contador-carrito').text(total);
            }

            // ——— Ejecutar contador al cargar la página
            actualizarContadorDesdeLocalStorage();

            // ——— Delegación: quitar producto del carrito
            $(document).on('click', '.quitar-carrito', function() {
                const id = $(this).data('id');
                const nombre = $(this).data('nombre');

                Swal.fire({
                    title: `¿Eliminar "${nombre}" del carrito?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then(result => {
                    if (result.isConfirmed) {
                        fetch(`/ventas/quitar-carrito/${id}`, {
                                method: 'GET',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(res => res.json())
                            .then(json => {
                                if (!json.success) throw new Error(json.message || 'Error');
                                quitarDelLocalStorage(id);
                                cargarCarrito();
                                $('#contador-carrito').text(json.total);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Eliminado',
                                    toast: true,
                                    position: 'top-end',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            })
                            .catch(err => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: err.message,
                                    toast: true,
                                    position: 'top-end',
                                    timer: 1500
                                });
                            });
                    }
                });
            });

            // ——— Botones de cantidad (+ / -)
            document.querySelectorAll('.btn-sumar').forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('.cantidad-input');
                    let cantidad = parseInt(input.value);
                    cantidad++;
                    input.value = cantidad;
                });
            });

            document.querySelectorAll('.btn-restar').forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('.cantidad-input');
                    let cantidad = parseInt(input.value);
                    if (cantidad > 1) cantidad--;
                    input.value = cantidad;
                });
            });


            $('.agregar-carrito').on('click', function() {
                const btn = $(this).prop('disabled', true);
                const id = btn.data('id');
                const nombre = btn.data('nombre');
                const codigo = btn.data('codigo');
                const imagen = btn.data('imagen_producto');
                const precio = parseFloat(btn.data('precio_venta'));
                const qty = parseInt($(`#cantidad_${id}`).val(), 10);

                if (isNaN(qty) || qty < 1) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Cantidad inválida',
                        toast: true,
                        position: 'top-end',
                        timer: 2000
                    });
                    btn.prop('disabled', false);
                    return;
                }

                fetch(`/ventas/agregar-carrito/${id}?cantidad=${qty}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(json => {
                        if (!json.success) throw new Error(json.message);

                        const producto = {
                            id,
                            nombre,
                            codigo,
                            imagen,
                            cantidad: qty,
                            precio
                        };

                        // ✅ Mostrar en consola lo que se guarda
                        console.log('🛒 Agregado al localStorage:', producto);

                        guardarEnLocalStorage(id, producto);

                        $('#contador-carrito').text(json.total);

                        Swal.fire({
                            icon: 'success',
                            toast: true,
                            position: 'top-end',
                            timer: 1800,
                            html: `<div style="display:flex;align-items:center">
                <img src="/storage/${imagen}" style="width:50px;height:50px;object-fit:cover;border-radius:5px;margin-right:10px">
                <div><strong>${nombre}</strong><br>Cantidad: ${qty}</div>
            </div>`
                        });
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: err.message,
                            toast: true,
                            position: 'top-end',
                            timer: 1800
                        });
                    })
                    .finally(() => btn.prop('disabled', false));
            });


        });
    </script>
@endpush
