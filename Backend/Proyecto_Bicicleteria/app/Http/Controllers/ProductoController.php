<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // Display
    public function index()
    {
        // listas de productos
        $productos = Producto::orderBy('id', 'desc')->get();
        return view('productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('productos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validar datos
        $data = $request->validate([
            'nombre'      =>'required|string|max:255',
            'descripcion' =>'nullable|string',
            'stock'       =>'required|integer|min:0',
            'stock_minimo'=>'required|integer|min:0',
            'precio'      =>'required|numeric|min:0',
        ]);

        Producto::create($data);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Producto::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //Buscar Producto
        $producto=Producto::FindOrFail($id);

        //Enviar al visual
        return view('productos.edit', compact('producto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $data = $request->validate([
            'nombre'        => 'required|string|max:255',
            'descripcion'   => 'nullable|string',
            'stock'         => 'required|integer|min:0',
            'stock_minimo'  => 'required|integer|min:0',
            'precio'        => 'required|numeric|min:0',
        ]);

        $producto = Producto::FindOrFail($id);
        $producto->update($data);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $producto = Producto::FindOrFail($id);
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('Success','Producto eliminado');
    }
}
