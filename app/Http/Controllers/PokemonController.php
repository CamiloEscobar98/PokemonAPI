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

    public function index($offset, $limit = 10)
    {
        return $this->pokemonList($offset, $limit);
    }

    public function show()
    {
        # code...
    }
}
