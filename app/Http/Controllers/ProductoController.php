<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Tienda;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{

        public function store(Request $request, $tiendaId)
        {
            $request->validate([
                'nombre' => 'required|string',
                'descripcion' => 'required|string',
                'precio' => 'required|numeric',
                'stock' => 'required|integer',
            ]);

            $tienda = Tienda::findOrFail($tiendaId);

            if ($tienda->vendedor_id !== Auth::id()) {
                return response()->json(['message' => 'No autorizado'], 403);
            }

            $producto = Producto::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'precio' => $request->precio,
                'stock' => $request->stock,
                'tienda_id' => $tiendaId,
            ]);

            return response()->json(['message' => 'Producto creado exitosamente', 'producto' => $producto], 201);
        }


        public function show($id)
        {
            $producto = Producto::findOrFail($id);
            return response()->json($producto);
        }


        public function update(Request $request, $id)
        {
            $producto = Producto::findOrFail($id);

            if ($producto->tienda->vendedor_id !== Auth::id()) {
                return response()->json(['message' => 'No autorizado'], 403);
            }

            $producto->update($request->all());

            return response()->json(['message' => 'Producto actualizado exitosamente', 'producto' => $producto]);
        }


        public function destroy($id)
        {
            $producto = Producto::findOrFail($id);

            if ($producto->tienda->vendedor_id !== Auth::id()) {
                return response()->json(['message' => 'No autorizado'], 403);
            }

            $producto->delete();

            return response()->json(['message' => 'Producto eliminado exitosamente']);
        }
}
