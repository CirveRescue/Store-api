<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrito;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;

class CarritoController extends Controller
{
    public function agregarProducto(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $producto = Producto::findOrFail($request->producto_id);

        if ($producto->stock < $request->cantidad) {
            return response()->json(['message' => 'No hay suficiente stock'], 400);
        }

        $carrito = Carrito::create([
            'cliente_id' => Auth::id(),
            'producto_id' => $request->producto_id,
            'cantidad' => $request->cantidad,
        ]);

        return response()->json(['message' => 'Producto agregado al carrito', 'carrito' => $carrito], 201);
    }
    public function show()
    {
        $clienteId = Auth::id();
        $carritos = Carrito::where('cliente_id', $clienteId)->with('producto')->get();

        return response()->json($carritos);
    }


    public function eliminarProducto($id)
    {
        $carrito = Carrito::findOrFail($id);

        if ($carrito->cliente_id !== Auth::id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $carrito->delete();

        return response()->json(['message' => 'Producto eliminado del carrito']);
    }
}
