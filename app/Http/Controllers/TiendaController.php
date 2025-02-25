<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tienda;
use Illuminate\Support\Facades\Auth;

class TiendaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'direccion' => 'required|string',
        ]);

        $tienda = Tienda::create([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'vendedor_id' => Auth::id(),
        ]);

        return response()->json(['message' => 'Tienda creada exitosamente', 'tienda' => $tienda], 201);
    }


    public function show($id)
    {
        $tienda = Tienda::findOrFail($id);
        return response()->json($tienda);
    }


    public function update(Request $request, $id)
    {
        $tienda = Tienda::find($id);

        // Verificar si la tienda existe
        if (!$tienda) {
            return response()->json(['message' => 'Tienda no encontrada'], 404);
        }

        // Verificar si el vendedor es el dueño de la tienda
        if ($tienda->vendedor_id !== Auth::id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Actualizar la tienda
        $tienda->update($request->all());

        return response()->json(['message' => 'Tienda actualizada exitosamente', 'tienda' => $tienda]);
    }


    public function destroy($id)
    {
        $tienda = Tienda::findOrFail($id);

        if ($tienda->vendedor_id !== Auth::id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tienda->delete();

        return response()->json(['message' => 'Tienda eliminada exitosamente']);
    }
}
