<?php

namespace App\Http\Controllers;

use App\Traits\PokemonRequest;

class PokemonController extends Controller
{
    use PokemonRequest;
    public function __construct()
    {
        //
    }

    public function index()
    {
        return $this->pokemonList();
    }

    public function show()
    {
        # code...
    }
}
