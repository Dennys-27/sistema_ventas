<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de compra</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            background: #f9f9f9;
            padding: 0;
            margin: 0;
        }

        .ticket {
            width: 270px;
            margin: 20px auto;
            background: #fff;
            padding: 20px 18px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
            color: #333;
        }

        .header small {
            font-size: 11px;
            color: #888;
        }

        .info {
            margin-bottom: 12px;
            border-top: 1px dashed #ccc;
            border-bottom: 1px dashed #ccc;
            padding: 8px 0;
        }

        .info p {
            margin: 3px 0;
            font-size: 11px;
            color: #444;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            font-size: 11px;
            padding: 4px 0;
            color: #555;
            border-bottom: 1px solid #ccc;
        }

        td {
            padding: 4px 0;
            border-bottom: 1px dashed #eee;
            font-size: 11px;
        }

        td:nth-child(2),
        td:nth-child(3),
        td:nth-child(4) {
            text-align: right;
        }

        .total {
            margin-top: 12px;
            text-align: right;
            font-weight: bold;
            font-size: 13px;
            color: #000;
        }

        .gracias {
            text-align: center;
            font-size: 12px;
            margin-top: 14px;
            color: #555;
            font-style: italic;
        }

        .linea {
            border-top: 1px dashed #ccc;
            margin: 12px 0;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <h1>Tomilboot</h1>
            <small>Ticket de compra</small>
        </div>

        <div class="info">
            <p><strong>Cajero:</strong> {{ $venta->nombre_usuario }}</p>
            <p><strong>Fecha:</strong> {{ $venta->created_at }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cant.</th>
                    <th>Precio</th>
                    <th>Subt.</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detalles as $item)
                    <tr>
                        <td>{{ $item->nombre_producto }}</td>
                        <td>{{ $item->cantidad }}</td>
                        <td>${{ number_format($item->precio_unitario, 2) }}</td>
                        <td>${{ number_format($item->sub_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            Total: ${{ number_format($venta->total_venta, 2) }}
        </div>

        <div class="gracias">
            ¡Gracias por su compra!
        </div>
    </div>
</body>
</html>
