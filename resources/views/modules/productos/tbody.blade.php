 @foreach ($items as $item)
     <tr>
         <td>{{ $item->nombre_categoria }}</td>
         <td>{{ $item->nombre_proveedor }}</td>
         <td>{{ $item->nombre }}</td>
         <td>
             <img src="{{ asset('storage/' . $item->imagen_producto) }}" alt="" width="60px" height="60px">

             @if ($item->imagen_id)
                 <a href="{{ route('productos.show.image', ['id' => $item->imagen_id]) }}"
                     class="badge rounded-pill bg-info text-dark">
                     <i class="fa-solid fa-image"></i> Ver Imagen
                 </a>
             @else
                 <span class="badge rounded-pill bg-secondary">Sin imagen</span>
             @endif
         </td>

         <td>{{ $item->descripcion }}</td>
         <td>{{ $item->cantidad }}</td>
         <td>{{ $item->precio_venta }}</td>
         <td>{{ $item->precio_compra }}</td>
         <td class="text-center">
             <div class="form-check form-switch">
                 <input class="form-check-input" type="checkbox" id="{{ $item->id }}"
                     {{ $item->activo ? 'checked' : '' }}>
             </div>
         </td>
         <td>
             <a href="{{ route('compras-create', ['id_producto' => $item->id]) }}" class="btn btn-success btn-sm">
                 <i class="fa-solid fa-cart-shopping"></i>
             </a>
         </td>


         <td><a href="{{ route('productos-edit', $item->id) }}" class="btn btn-warning btn-sm"><i
                     class="fa-solid fa-pen-to-square"></i></a>
             <a href="{{ route('productos-show', $item->id) }}" class="btn btn-danger btn-sm"><i
                     class="fa-solid fa-trash-can"></i></a>
         </td>
     </tr>
 @endforeach
