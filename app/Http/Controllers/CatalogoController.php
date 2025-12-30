<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Categoria;

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        $query = Produto::where('estoque', '>=', 1)->where('foto', '!=', '')->where('foto', '!=', '[]');
            
        
        // Filtro por categoria
        if ($request->has('categoria') && $request->categoria != 'todas') {
            $query->whereHas('categoria', function($q) use ($request) {
                $q->where('nome', $request->categoria);
            });
        }
        
        // Filtro por busca
        if ($request->has('busca')) {
            $query->where(function($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->busca . '%')
                  ->orWhere('codbar', 'like', '%' . $request->busca . '%');
            });
        }
        
        $produtos = $query->paginate(9);
        $categorias = Categoria::has('produtos')->get();
        
        return view('public.catalogo', compact('produtos', 'categorias'));
    }
}