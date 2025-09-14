<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function register_client(Request $request)
    {
        $validated = $request->validate([
            'nombre'   => 'required|string|max:255',
            'email'    => 'required|email|unique:clientes,email',
            'telefono' => 'nullable|string|max:20',
            'direccion'=> 'nullable|string|max:255',
        ]);

        $cliente = Cliente::create($validated);

        return response()->json([
            'message' => 'Cliente registrado correctamente',
            'data' => $cliente
        ], 201);
    }
}
