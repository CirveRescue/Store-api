<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Vendedor;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $request->validate([
            'nombre' => 'required|string',
            'email' => 'required|email|unique:clientes,email|unique:vendedores,email',
            'contrasena' => 'required|string',
            'tipo' => 'required|in:cliente,vendedor',
        ]);
        if ($request->tipo === 'cliente') {
            Cliente::create([
                'nombre' => $request->nombre,
                'email' => $request->email,
                'contrasena' => Hash::make($request->contrasena),
            ]);
        } else {
            Vendedor::create([
                'nombre' => $request->nombre,
                'email' => $request->email,
                'contrasena' => Hash::make($request->contrasena),
            ]);
        }


        return response()->json(['message' => 'Usuario registrado exitosamente'], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'contrasena' => 'required|string',
            'tipo' => 'required|in:cliente,vendedor',
        ]);

        if($request->tipo === 'cliente'){
            $user = Cliente::where('email', $request->email)->first();
        }else{
            $user = Vendedor::where('email', $request->email)->first();
        }

        if (!$user || !Hash::check($request->contrasena, $user->contrasena)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout exitoso']);
    }
    
}
