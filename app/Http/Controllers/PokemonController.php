<?php

namespace App\Http\Controllers;

use App\Traits\PokemonRequest;
use Illuminate\Http\Request;

class PokemonController extends Controller
{
    use PokemonRequest;
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        $this->validate($request, [
            'offset' => ['required', 'numeric'],
            'limit' => ['numeric'],
        ]);
        return $this->pokemonList(offset: $request->get('offset'), limit: $request->get('limit', 10));
    }

    public function show(Request $request)
    {
        $this->validate($request, [
            'url' => ['required', 'url']
        ]);

        return $this->pokemonInfo($request->url());
    }
}
