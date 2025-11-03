<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 🟢 Listar todos os produtos
    public function index()
    {
        return response()->json(Product::with('category')->get());
    }

    // 🟢 Criar novo produto
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::create($validated);
        return response()->json($product, 201);
    }

    // 🟢 Mostrar um produto específico
    public function show(Product $product)
    {
        return response()->json($product->load('category'));
    }

    // 🟢 Atualizar produto existente
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'price' => 'numeric',
            'category_id' => 'exists:categories,id',
        ]);

        $product->update($validated);
        return response()->json($product);
    }

    // 🟢 Excluir produto 
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Produto excluído com sucesso!']);
    }
}
