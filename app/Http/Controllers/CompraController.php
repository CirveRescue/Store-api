<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrito;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Producto;
use App\Models\Tienda; 
use Illuminate\Support\Facades\Auth;

class CompraController extends Controller
{
    public function finalizarCompra(Request $request)
    {
        $clienteId = Auth::id();
        $carritos = Carrito::where('cliente_id', $clienteId)->get();

        if ($carritos->isEmpty()) {
            return response()->json(['message' => 'El carrito está vacío'], 400);
        }

        $compra = Compra::create([
            'cliente_id' => $clienteId,
            'fecha' => now(),
            'total' => 0,
        ]);

        $total = 0;

        foreach ($carritos as $carrito) {
            $producto = Producto::findOrFail($carrito->producto_id);

            if ($producto->stock < $carrito->cantidad) {
                return response()->json(['message' => 'No hay suficiente stock para ' . $producto->nombre], 400);
            }

            $producto->stock -= $carrito->cantidad;
            $producto->save();

            $subtotal = $producto->precio * $carrito->cantidad;
            $total += $subtotal;

            DetalleCompra::create([
                'compra_id' => $compra->id,
                'producto_id' => $producto->id,
                'cantidad' => $carrito->cantidad,
                'precio_unitario' => $producto->precio,
            ]);

            $carrito->delete();
        }

        $compra->total = $total;
        $compra->save();

        return response()->json(['message' => 'Compra finalizada exitosamente', 'compra' => $compra], 201);
    }

    public function historialCompras()
    {
        $clienteId = Auth::id();
        $compras = Compra::where('cliente_id', $clienteId)->with('detalles.producto')->get();

        return response()->json($compras);
    }

    public function historialVentas()
    {
        $vendedorId = Auth::id();
        $tiendas = Tienda::where('vendedor_id', $vendedorId)->pluck('id');
        $compras = Compra::whereHas('detalles.producto', function ($query) use ($tiendas) {
            $query->whereIn('tienda_id', $tiendas);
        })->with('detalles.producto')->get();

        return response()->json($compras);
    }
}
